---
title: "Why I Removed the Service Container from Console Applications"
slug: removing-service-container-from-console-applications
date: 2025-02-22
description: "The service container offers powerful features — but do you always need them? Removing it from console applications leads to simpler, more testable, more minimal code."
keywords: "Symfony console application, service container, dependency injection, PHP testable code, PHP minimalism, Symfony DI container"
---

The service container gives us a lot: Dependency Injection, Service Definition & Management, Autowiring, Autoconfiguration, Service Tags, Lazy Loading, and PSR-11 Compatibility.

Very nice. Very powerful. But do you actually need all of it?

More often than not, the honest answer is: no.

## The problem with container-everything

When building a console application with the service container, you inject services via constructors and method parameters. This is so normal it becomes automatic — inject, inject, inject.

Then you go to write tests. Now you have to mock many of those injected services. Mocking creates its own set of problems — tests that pass against mocks but fail against real implementations, brittle test setups, and false confidence.

Fundamentally, the container makes it hard to instantiate objects directly in tests and tends to produce bloated applications where the dependency graph is implicit rather than visible.

## What it looks like without a container

A minimal console application needs just two files.

`console.php`:

```php
declare(strict_types=1);

use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new SomeCommand(project: new SomeObject()));

try {
    $application->run();
} catch (Exception) {}
```

Console bin file:

```php
#!/usr/bin/env php
<?php
require __DIR__ . '/console.php';
```

You might think: "Now I have to wire everything manually." That's exactly the point. Wiring things manually forces you to think carefully about what's actually necessary and to write objects that are genuinely small and focused.

## Testing becomes trivial

Without mocking infrastructure in the way, a test can instantiate objects directly:

```php
public function testCalculateTotalScore(): void
{
    $project = new Project();
    $upgrade = new Upgrade();
    $project->setUpgrade($upgrade);
    $upgrade->setFrameworkVersionUpgradabilityScore(80);
    $upgrade->setDependenciesUpgradabilityScore(60);
    $upgrade->setPhpVersionUpgradabilityScore(90);
    $upgrade->setCodebaseSizeUpgradabilityScore(70);

    $upgradeCalculator = new UpgradeCalculator();
    $result = $upgradeCalculator->calculateTotalScore(project: $project);

    $this->assertEquals(round(63.5, 2), $result);
}
```

No mocking. The test instantiates exactly what it needs and tests exactly what it says it tests.

## What about Single Responsibility?

This is a fair objection. Without the container, you may end up passing objects like `SymfonyStyle` to classes that handle computation — which feels like a violation of SRP.

Consider the alternative: to avoid that violation, you'd need to:

1. Create an event
2. Dispatch the event
3. Create a subscriber to handle it
4. Have the subscriber update the progress bar

That requires the EventDispatcher component. All of that added complexity, rather than passing one argument to a method? In a small console tool, that tradeoff isn't worth it.

## The principle

The service container is fantastic and has many legitimate use cases. But in small, focused console applications — the kind used for analysis tools, migration scripts, or one-purpose CLIs — the container adds overhead without adding value.

Writing tested, high-quality, minimalist applications that require less maintenance is the goal. The container should be a deliberate choice, not the default.
