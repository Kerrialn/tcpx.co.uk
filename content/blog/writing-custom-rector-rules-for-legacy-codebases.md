---
title: "Writing Custom Rector Rules for Legacy PHP Codebases"
slug: writing-custom-rector-rules-for-legacy-codebases
date: 2026-04-14
description: "How to write custom Rector rules using AST node visitors to automate refactoring of proprietary patterns in legacy PHP codebases that standard rule sets don't cover."
keywords: "custom Rector rules, Rector PHP tutorial, Rector AST, write Rector rule, Rector node visitor, PHP refactoring automation, Rector custom rule example"
---

Rector ships with hundreds of built-in rules covering PHP version upgrades, Symfony migrations, PHPUnit upgrades, dead code removal, and more. For most PHP 7 → 8 upgrade work, you configure the appropriate rule sets and run. But large legacy codebases always contain proprietary patterns — internal helper functions, custom base classes, bespoke service locators — that standard rules don't touch. This is where custom Rector rules become essential.

This article walks through writing a custom Rector rule from scratch: understanding the AST, building a node visitor, and testing the rule before applying it.

## How Rector works internally

Rector parses each PHP file using [nikic/php-parser](https://github.com/nikic/PHP-Parser), which produces an Abstract Syntax Tree (AST) — a structured representation of the code as a tree of objects. Each node in the tree represents a PHP construct: a class, a method, a function call, an expression.

A Rector rule is a class that:
1. Declares which AST node types it is interested in
2. Receives matching nodes and inspects them
3. Returns a modified node (or `null` to leave it unchanged)

Rector handles the file reading, parsing, applying changes, and writing. Your rule only handles the transformation logic.

## A concrete example: replacing a legacy service locator

Suppose your codebase has a custom `ServiceLocator::get()` pattern that should be replaced with constructor injection. Every occurrence looks like:

```php
// Before
$service = ServiceLocator::get(PaymentGateway::class);

// After (target)
// $this->paymentGateway — injected via constructor
```

This is too context-specific for any standard rule. We need a custom one.

## Setting up the rule

Create a file in your project — outside the `src` directory, typically in `utils/rector/` — and create a class extending `AbstractRector`:

```php
<?php

namespace Utils\Rector\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

final class ServiceLocatorToPropertyRector extends AbstractRector implements ConfigurableRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace ServiceLocator::get() calls with a flag for manual review',
            [
                new CodeSample(
                    'ServiceLocator::get(PaymentGateway::class);',
                    '/* TODO: inject PaymentGateway via constructor */'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof StaticCall) {
            return null;
        }

        if (!$this->isName($node->class, 'ServiceLocator')) {
            return null;
        }

        if (!$this->isName($node->name, 'get')) {
            return null;
        }

        // Return null to skip, or return a modified node.
        // In this case we add a comment flagging it for manual review.
        $node->setAttribute('comments', [
            new \PhpParser\Comment\Doc('/** @todo Replace with constructor injection */'),
        ]);

        return $node;
    }
}
```

## Registering the custom rule

In your `rector.php` config, register the rule:

```php
use Utils\Rector\Rule\ServiceLocatorToPropertyRector;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withRules([
        ServiceLocatorToPropertyRector::class,
    ]);
```

And add the `utils/rector` namespace to your `composer.json` autoload-dev:

```json
{
  "autoload-dev": {
    "psr-4": {
      "Utils\\Rector\\": "utils/rector/"
    }
  }
}
```

Run `composer dump-autoload` and then test with `--dry-run`.

## Testing your rule

Rector has a built-in testing infrastructure for rules. Create a test file:

```php
<?php

namespace Utils\Rector\Tests\Rule;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ServiceLocatorToPropertyRectorTest extends AbstractRectorTestCase
{
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
```

Create a fixture file at `tests/Rule/Fixture/service_locator.php.inc`:

```php
<?php

class SomeClass
{
    public function doSomething()
    {
        $gateway = ServiceLocator::get(PaymentGateway::class);
    }
}

?>
-----
<?php

class SomeClass
{
    public function doSomething()
    {
        /** @todo Replace with constructor injection */
        $gateway = ServiceLocator::get(PaymentGateway::class);
    }
}
```

The `-----` divider separates input from expected output. Run the test with PHPUnit. Once it passes, you know the rule behaves exactly as expected before you run it on production code.

## More useful custom rule patterns

Beyond service locator replacement, common custom rules we write in legacy codebases include:

**Replacing internal helper functions:**
```php
// Before
helper_format_date($date, 'Y-m-d');

// After
$date->format('Y-m-d');
```

**Updating deprecated internal base class method calls:**
```php
// Before
$this->getDb()->fetch('SELECT ...');

// After
$this->connection->fetchAssociative('SELECT ...');
```

**Adding missing type declarations to known patterns:**
```php
// Before (all methods in classes extending BaseEntity have known return types)
public function getId() { return $this->id; }

// After
public function getId(): int { return $this->id; }
```

**Removing deprecated logging calls:**
```php
// Before
Logger::getInstance()->log($message);

// After
$this->logger->info($message);
```

## When custom rules save the most time

Custom Rector rules deliver the biggest return on investment when:

1. **The pattern appears hundreds or thousands of times** — even 10 minutes per manual fix becomes days at scale
2. **The pattern is consistent** — if it varies, the rule becomes complex and error-prone
3. **The transformation is mechanical** — rules handle syntax and structure; they can't make judgement calls

For patterns that appear fewer than 50 times, manual refactoring with custom rules may not be worth the setup cost. For patterns at the 500+ occurrence scale, a well-tested custom rule pays for itself immediately.

If your codebase has proprietary patterns that standard Rector rule sets don't handle, that's a core part of what we scope in a discovery engagement. [Get in touch](/contact) to discuss automated refactoring for your legacy PHP project.
