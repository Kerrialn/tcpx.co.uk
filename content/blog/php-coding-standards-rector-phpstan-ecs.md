---
title: "PHP Coding Standards: Rector, PHPStan, and Easy Coding Standard"
slug: php-coding-standards-rector-phpstan-ecs
date: 2024-04-08
description: "What static analysis tools are available for PHP, how to install and configure Rector, PHPStan, and Easy Coding Standard, and how together they create consistently high-quality code with minimal effort."
keywords: "PHP coding standards, Rector PHP, PHPStan, Easy Coding Standard, PHP static analysis, automated PHP refactoring, PHP code quality"
---

You may have heard terms like static analysis, Rector, Easy Coding Standards, and PHPStan thrown around. But what are they, what do they actually do, and how do you use them?

Static analysis refers to the process of analysing source code without executing it. A static analysis tool looks at your code but doesn't run it — it reasons about what the code *means* rather than what it *does at runtime*.

## The three tools

There are many static analysis tools for PHP, but this combination provides excellent coverage with minimal overlap:

- [**Rector**](https://packagist.org/packages/rector/rector) — Instant upgrade and automated refactoring of any PHP code
- [**Easy Coding Standard**](https://packagist.org/packages/symplify/easy-coding-standard) — Use PHP-CS-Fixer and PHP_CodeSniffer rules with zero configuration knowledge required
- [**PHPStan**](https://packagist.org/packages/phpstan/phpstan) — Finds errors in your code without actually running it

Using these tools automates the process of finding problems. Run them from the terminal and they either fix problems automatically or tell you precisely where the problem is.

## Rector

Rector is great at fixing or upgrading your code's *functionality* automatically.

```bash
composer require --dev rector/rector
```

Run it:

```bash
vendor/bin/rector p --ansi
```

To preview changes without applying them:

```bash
vendor/bin/rector p --dry-run --ansi
```

On first run, Rector will offer to create a `rector.php` config file. A solid general-purpose configuration:

```php
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        SetList::PHP_83,
        SetList::TYPE_DECLARATION,
        SetList::DEAD_CODE,
    ]);
```

A *set* is a collection of rules; a *rule* defines a specific code transformation. You don't need to write the rules — Rector ships with hundreds. Check the `SetList` class to see everything available.

Commit after each Rector run so you can revert if needed.

## Easy Coding Standard

ECS is great at fixing your code's *style and formatting*.

```bash
composer require --dev symplify/easy-coding-standard
```

Check for issues:

```bash
vendor/bin/ecs check --ansi
```

Check and fix:

```bash
vendor/bin/ecs check --fix --ansi
```

On first run it will offer to generate an `ecs.php` config file. A working baseline:

```php
return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPreparedSets(
        arrays: true,
        comments: true,
        docblocks: true,
        spaces: true,
        namespaces: true,
    );
```

## PHPStan

PHPStan is great at finding *errors* and telling you precisely where they are.

```bash
composer require --dev phpstan/phpstan
```

Run it:

```bash
vendor/bin/phpstan analyse
```

PHPStan doesn't auto-generate its config file, so create `phpstan.dist.neon` manually:

```neon
parameters:
    level: 6
    paths:
        - bin/
        - config/
        - public/
        - src/
        - tests/
```

Level 6 is a solid starting point. Level 8 is the maximum but gets aggressive. The sweet spot for most projects is 6–7.

## How the three tools complement each other

- **PHPStan** finds logical errors and type issues — things that are wrong
- **Rector** fixes functional issues and upgrades patterns — things that are outdated
- **ECS** fixes formatting and style — things that look inconsistent

Together they produce excellent cross-coverage and result in clean, type-safe, consistently formatted code with minimal manual effort. Run them in CI as a gate on every PR and the quality floor of your codebase never drops.
