---
title: "How to Upgrade PHP 7 to PHP 8 with Rector"
slug: how-to-upgrade-php-7-to-8-with-rector
date: 2026-03-10
description: "A practical guide to upgrading PHP 7 to PHP 8 using Migrator for pre-flight assessment and Rector for automated transformation: configuring rule sets, running safe batches, and handling edge cases your tests won't catch."
keywords: "PHP 7 to PHP 8 upgrade, Rector PHP 8, automated PHP upgrade, PHP 8 migration guide, Rector tutorial, Migrator PHP, kerrialn/migrator"
---

Upgrading a large PHP codebase from PHP 7 to PHP 8 manually is a project measured in months and carries a high risk of introducing subtle bugs. The right approach starts with knowing exactly what you're facing — before touching a line of code — and then automating as much of the transformation as possible with Rector. This guide covers the full process: assessing complexity with [Migrator](https://github.com/Kerrialn/migrator), configuring Rector, running it safely, and handling the cases where automation alone isn't enough.

## Step 0: Assess upgrade complexity with Migrator

Before you configure Rector or touch any code, you need to understand the scope of the upgrade. [Migrator](https://github.com/Kerrialn/migrator) is a command-line tool that analyses your codebase and reports the complexity of upgrading or migrating your project — highlighting potential challenges, dependencies, and necessary changes.

Install it:

```bash
composer require kerrialn/migrator
```

Run the analyser against your project root:

```bash
vendor/bin/migrator analyse /path/to/your/project
```

Migrator checks your codebase against the target PHP version, surfacing: deprecated function usage, removed APIs, dependency compatibility issues, and patterns that will require manual intervention. The output tells you what Rector will handle automatically and what will need human decisions — before you commit to a timeline or approach.

Run Migrator before every upgrade engagement. It prevents false confidence in scope estimates and surfaces the edge cases early.

## What Rector does (and doesn't) do

Rector operates on your code's Abstract Syntax Tree. It parses each PHP file, applies transformation rules, and writes the modified file back. Because it understands types and scope — not just text patterns — it can make semantically correct changes that a regex never could.

For a PHP 7 → 8 upgrade, Rector can handle:

- Replacing `strpos() !== false` with `str_contains()`
- Adding union types where types are inferred
- Converting `array_key_exists()` to nullsafe operators where appropriate
- Removing `{}` string access in favour of `[]`
- Flagging `match` expression candidates
- Removing redundant `@` suppression operators

What it cannot do automatically: resolve ambiguous types, fix logic errors that PHP 7 permitted but PHP 8 rejects, or make architectural decisions about where `named arguments` should be introduced. Migrator's analysis output will have told you where these cases live.

## Setting up Rector

Install Rector as a dev dependency:

```bash
composer require rector/rector --dev
```

Create a `rector.php` configuration file in your project root:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_80,
    ]);
```

## Run Rector in dry-run mode first

Never apply Rector changes directly. Always run with `--dry-run` first:

```bash
vendor/bin/rector process --dry-run
```

This shows you every change Rector would make without touching a file. Compare this against your Migrator report — everything Migrator flagged as "automatable" should appear in Rector's diff. Anything Migrator flagged as requiring manual intervention won't appear here; those are your manual tasks.

Review the diff for:

- False positives (transformations that look correct but aren't)
- High-volume patterns that deserve their own isolated batch
- Files Rector cannot parse (syntax errors your tests may have missed)

## Apply changes in batches

Don't run all rules at once. Group transformations by risk level and apply them as separate commits:

**Batch 1: Safe, mechanical changes** — string function replacements, `{}` to `[]` array access, removing redundant casts. These are near zero risk.

**Batch 2: Type declarations** — adding `declare(strict_types=1)`, return types, and parameter types where inferred. Run your full test suite after each file-group.

**Batch 3: PHP 8 features** — `match` expressions, named arguments, nullsafe operators. These require more review as they can change behaviour in edge cases.

After each batch: commit, push, run CI.

## Handling edge cases

### Dynamic function calls

Rector can't safely transform `$fn = 'strpos'; $fn($a, $b)` — dynamic calls that resolve at runtime. Migrator will have flagged these. They need manual review.

### Deprecated internal extensions

PHP 8 removed several extensions and functions (e.g. `mysql_*` functions were already gone by 7.0, but `each()` was removed in 8.0). Migrator surfaces these in its analysis output with counts and file locations.

### Strict type coercion

PHP 8 tightened type coercion in `strict_types` mode. Functions that previously accepted `"1"` where `int` was expected will now throw a `TypeError`. This is correct behaviour, but it surfaces hidden bugs. Migrator will indicate where `strict_types` coercion changes will bite you.

## Wire Rector into CI

Once you've completed the initial upgrade, add Rector to your CI pipeline in `--dry-run` mode to prevent regression:

```yaml
- name: Rector check
  run: vendor/bin/rector process --dry-run
```

Any future PR that reintroduces a deprecated pattern will fail the build.

## The full toolchain

A complete PHP 7 → 8 upgrade engagement typically combines:

| Tool | Role |
|---|---|
| **Migrator** | Pre-flight: assess scope, surface manual tasks, estimate effort |
| **Rector** | Automated code transformation: syntax, types, deprecated APIs |
| **PHPStan** | Type safety validation after Rector runs |
| **Indoctrinate** | Database layer: sync schema with Doctrine specs alongside the code upgrade |

The database layer deserves a separate pass. If your PHP upgrade is happening at the same time as an ORM migration, use [Indoctrinate](https://github.com/Kerrialn/indoctrinate) to analyse and fix schema inconsistencies in parallel with the Rector work.

## When to bring in specialists

Rector and Migrator together handle the mechanical work well. The cases that require specialist knowledge are:

- **Custom Rector rules** — when your codebase has proprietary patterns that standard rule sets don't cover
- **Architecture decisions** — choosing where to introduce `readonly` classes, enums, and fibers requires understanding your domain
- **Type inference at scale** — getting full type coverage across a 500k-line codebase requires PHPStan integration and often manual type annotation work
- **High Migrator complexity scores** — when Migrator reports a large volume of manual-intervention cases, that's a signal the upgrade needs senior PHP architecture input, not just automation

If you're working with a large legacy codebase, a discovery engagement scopes this precisely before you commit to a timeline. [Get in touch](/contact) to discuss your PHP 8 upgrade.
