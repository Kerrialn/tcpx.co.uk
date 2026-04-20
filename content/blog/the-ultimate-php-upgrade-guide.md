---
title: "The Ultimate PHP Upgrade Guide"
slug: the-ultimate-php-upgrade-guide
date: 2024-12-19
description: "A practical, actionable process for upgrading PHP projects: from risk assessment and roadmap implementation to cleanup and documentation, with Migrator, Rector, PHPStan, and ECS."
keywords: "PHP upgrade guide, PHP version upgrade, Rector PHP, PHPStan, PHP migration roadmap, automated PHP refactoring, kerrialn/migrator"
---

Upgrading a PHP project can be challenging and complex. Over the years of tackling numerous upgrades — sometimes alone, sometimes as part of a team — a clear process has emerged. This guide provides something practical, actionable, and easy to follow.

## 1. Analyse & Document

If you aren't familiar with the Awareness–Understanding matrix, it's a framework that helps identify areas where teams are aware or unaware of issues, and whether they understand how to address them. It's extremely useful for prioritising risks when upgrading.

Here's how it maps to a PHP upgrade:

- **Known knowns**: You're aware the upgrade to PHP 8.2 will break `each()` usage, and you understand how to replace it with `foreach`.
- **Unknown knowns**: A static analysis tool (e.g. PHPStan) flags deprecations you weren't aware of, but once you see them you know how to fix them.
- **Known unknowns**: You know that PHP 8.2 introduces readonly classes, but you don't fully understand how they impact your codebase.
- **Unknown unknowns**: A production bug arises after upgrading, and you're unaware of both its cause and how to debug it.

The more unknowns you have, the higher the risk — and the lower the likelihood of a clean upgrade.

### Start with a pre-flight assessment

Before touching any code, run [Migrator](https://github.com/Kerrialn/migrator) to assess your codebase's upgrade complexity:

```bash
composer require kerrialn/migrator
vendor/bin/migrator analyse
```

Migrator analyses your project and reports the complexity of upgrading — highlighting potential challenges, outdated dependencies, and PHP compatibility issues. Use its output to build your risk assessment across each version step.

For each minor version between your current PHP and target (e.g. 7.2 → 7.3 → 7.4 → 8.0 → 8.1 → 8.2), assess:

- What changed in the language? New features, deprecations, breaking changes?
- How heavily does the codebase rely on deprecated or changed features?
- How many third-party packages are outdated or incompatible with this version?
- Are the current features well-tested?
- How complex is the upgrade process for the identified changes?
- How time-consuming will it be to resolve these issues?

Install these tools and run them as part of your analysis:

```bash
composer require --dev phpmetrics/phpmetrics
composer require --dev phpstan/phpstan
composer require --dev rector/rector
composer require --dev symplify/easy-coding-standard
```

- **phpmetrics/phpmetrics** — shows where your application logic is most complex and therefore hardest to upgrade
- **phpstan/phpstan** — finds errors in your code without running it
- **rector/rector** — automatically finds and fixes compatibility issues
- **symplify/easy-coding-standard** — enforces coding standards and formats code

Don't get stuck collecting data. Once you have enough to understand the risk associated with each version step, you have enough to start.

## 2. Implement the Roadmap

Assuming the data indicates an upgrade is worthwhile, the next phase is implementation.

Before you begin, set up:

1. A backup and rollback strategy — a git branch plus a separate downloaded copy of the project
2. CI checks on the migration branch (GitHub Actions, etc.) — verify all checks pass before merging
3. Your development environment — Docker if you're in a large team

### The incremental process

Work version by version. For a PHP 7.2 application targeting 7.3:

1. Create a branch `upgrade-to-7.3`
2. Update the PHP version constraint in the `require` section of `composer.json` to `^7.3`
3. If you use a framework, update it to the version supported by PHP 7.3
4. Run `composer update -W`

Resolve any dependency conflicts and repeat until it installs. The application may break — that's expected.

Now iterate on improvements within this branch:

1. **Run your tests** to find what's broken, then cross-reference with your roadmap. Start with the easiest fixes.
2. **Use Rector in iterative passes** for each concern. For example, to add type coverage incrementally:

```php
// rector.php
return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withTypeCoverageLevel(1);
```

Start at level 1, increment by 10, check the diff after each run, fix issues, run your coding standards checks, then commit.

3. **For complex repetitive changes** (e.g. converting 200 entity IDs to a `Uuid` type), write a custom Rector rule. Add it to the config and run it across the whole codebase at once.
4. **Stay in scope.** Focus only on the current task. Getting distracted by unrelated issues is how upgrades spiral into legacy rabbit holes.
5. Merge into the version branch once CI passes. Then pick the next item from the roadmap.

Merge each major version into `main` independently. Avoid maintaining a single enormous upgrade branch — it will be a nightmare to merge.

## 3. Clean Up & Document

The upgrade is nearly complete. Now tie up loose ends:

1. **Remove dead code** — use Rector's `withDeadCodeLevel()` setting
2. **Review and update tests** — some features may have changed significantly or been removed
3. **Final regression testing** — confirm the application actually works end-to-end, not just unit-test green
4. **Changelog & versioning** — use your roadmap from Step 1 as the changelog between versions, and update project version tags accordingly

By following this process you're not only increasing the likelihood of a smooth upgrade, but setting yourself up for long-term maintainability. Clean code and clear documentation will make the next upgrade — and everyone who works on the project — far less painful.
