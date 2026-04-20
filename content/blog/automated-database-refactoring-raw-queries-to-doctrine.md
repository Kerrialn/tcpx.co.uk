---
title: "Automated Database Refactoring: Converting Raw PHP Queries to Doctrine"
slug: automated-database-refactoring-raw-queries-to-doctrine
date: 2026-03-24
description: "How to safely migrate raw SQL queries scattered across a legacy PHP codebase to Doctrine ORM using Indoctrinate: the dual-path pattern, repository introduction, and incremental cutover without big-bang risk."
keywords: "PHP database refactoring, raw SQL to Doctrine, PHP Doctrine migration, ORM migration PHP, database layer modernisation PHP, legacy PHP database upgrade, Indoctrinate PHP, kerrialn/indoctrinate"
---

A large legacy PHP application typically has SQL queries distributed across hundreds of files. Controllers, service classes, model files, even view helpers — anywhere the developer needed data, a query went in. Migrating this to Doctrine ORM isn't a find-and-replace operation. Done wrong it introduces bugs in business-critical paths. Done right it gives you a testable, maintainable persistence layer that survives framework upgrades.

This article describes the incremental approach we use: automated schema analysis with [Indoctrinate](https://github.com/Kerrialn/indoctrinate), interface-first abstraction, dual-path validation, and cutover.

## Step 1: Automated schema analysis with Indoctrinate

Before writing any code, you need a complete picture of your schema's current state — where it diverges from Doctrine's expectations and where inconsistencies exist that will cause mapping problems later.

[Indoctrinate](https://github.com/Kerrialn/indoctrinate) is an automated tool that synchronises your database with Doctrine specifications and surfaces schema inconsistencies. Install it:

```bash
composer require kerrialn/indoctrinate
```

Create a configuration file at `config/indoctrinate.php`:

```php
<?php

use Indoctrinate\Config\DbFixerConfig;

return static function (DbFixerConfig $config): void {
    $config->connection(
        driver: 'pdo_mysql',
        host: 'localhost',
        port: 3306,
        dbname: 'your_database',
        user: 'your_user',
        password: 'your_password',
    );

    $config->rules([
        \Indoctrinate\Rule\EnsureAutoIncrementPrimaryKeyRule::class => [
            'excludes' => ['%session%', '%cache%'],
        ],
    ]);
};
```

Run a dry-run first to see what it would change without touching anything:

```bash
php bin/indoctrinate fix --dry
```

Then apply fixes:

```bash
php bin/indoctrinate fix --log=var/log/indoctrinate
```

Indoctrinate gives you a prioritised schema remediation list: normalisation violations, missing auto-increment primary keys, column-to-type mismatches, and constraint gaps. This is your starting inventory — the database state Doctrine will need to map against.

## Step 2: Inventory every raw query in the codebase

With the schema picture from Indoctrinate, now map the PHP side: every `mysqli_query`, `PDO::query`, `PDO::prepare`, `pg_query`, and any custom query-runner method your application wraps.

Group them by table (or primary entity). The goal is to understand which tables are accessed by how many different query patterns, and which access patterns are candidates for consolidation behind a repository.

A table accessed in 50 different ways through 50 different files needs a different strategy to one accessed in three consistent ways. Both are fixable; the complexity differs.

## Step 3: Define repository interfaces first

Before writing Doctrine entity mappings or `EntityRepository` classes, define the repository interface in PHP. This is pure domain code — no Doctrine dependency:

```php
interface OrderRepository
{
    public function findById(OrderId $id): ?Order;
    public function findByCustomer(CustomerId $customerId): array;
    public function save(Order $order): void;
}
```

This interface represents what your application actually needs from the database — not what the database happens to contain. It's the stable contract that both your application code and your persistence layer must agree on.

## Step 4: The dual-path pattern

The safest way to introduce a Doctrine repository is to run it alongside the existing raw query for a period. Both paths execute; results are compared; discrepancies are logged:

```php
class DualPathOrderRepository implements OrderRepository
{
    public function findById(OrderId $id): ?Order
    {
        $legacyResult = $this->legacy->findById($id);
        $doctrineResult = $this->doctrine->findById($id);

        if ($legacyResult !== $doctrineResult) {
            $this->logger->warning('Dual-path mismatch', [
                'id' => $id->value(),
                'legacy' => $legacyResult,
                'doctrine' => $doctrineResult,
            ]);
        }

        return $legacyResult; // legacy is authoritative until cutover
    }
}
```

Run this in production. Every mismatch is a bug in your Doctrine mapping — surface it now, not after cutover.

## Step 5: Doctrine entity mapping

While the dual-path runs, iterate on your Doctrine entity mapping until mismatches drop to zero. Common causes of mismatches in legacy databases:

- **Column name divergence** — legacy code uses `user_id`, Doctrine convention would use `userId`; you need explicit column mapping
- **Type coercion** — legacy code stores booleans as `tinyint(1)`, Doctrine maps them differently by default
- **Nullable columns** — legacy code treats empty string and null interchangeably; Doctrine's type system distinguishes them
- **Soft deletes** — `deleted_at` columns that legacy code filters manually and Doctrine needs a filter for
- **Missing primary keys** — Indoctrinate's `EnsureAutoIncrementPrimaryKeyRule` will have flagged these in Step 1; they must be fixed before Doctrine can map the entity

## Step 6: Schema normalisation alongside the ORM migration

While introducing the ORM, you have the clearest possible view of your schema's problems. Indoctrinate handles many of these automatically. For the structural normalisation work that requires a human decision:

- Splitting wide tables (50+ columns) into related entities
- Extracting value objects (e.g. address fields → `Address` embeddable)
- Adding proper foreign key constraints to replace application-level enforcement
- Indexing columns that are filtered or joined on in queries

Each schema change is applied as a reversible Doctrine migration. The expand-contract pattern keeps live traffic running during changes: add the new column, backfill it, switch reads to the new column, switch writes, drop the old column. Never drop in the same migration as the alter.

Re-run `php bin/indoctrinate fix --dry` after each batch of schema changes to verify the schema is now in a state Doctrine expects.

## Step 7: Cutover and legacy query removal

Once the dual-path shows zero mismatches across a meaningful production traffic window (typically one to two weeks), switch the authoritative path to Doctrine and remove the legacy implementation:

```php
class ProductionOrderRepository implements OrderRepository
{
    public function findById(OrderId $id): ?Order
    {
        return $this->doctrine->findById($id);
    }
}
```

Remove the dual-path wrapper. Remove the legacy query class. The raw SQL is gone.

## What this achieves

After this process, your database layer is:

- **Testable** — repositories can be tested with a test database or a mock, independently of the application
- **Upgradeable** — Doctrine entity classes are refactorable with tools like Rector; raw SQL is not
- **Auditable** — every query is behind an interface; there are no hidden query sites
- **Schema-versioned** — every change has a migration; the schema history is in your repository
- **Doctrine-consistent** — Indoctrinate can be run on any future schema drift to catch problems early

This is the foundation that makes subsequent PHP 8 upgrades, framework migrations, and architectural improvements safe to execute. Once raw queries are behind repository interfaces, Rector can upgrade the surrounding PHP code without touching persistence logic.

If your codebase has a raw query problem at scale, [get in touch](/contact) — we scope the inventory and migration path before committing to a timeline.
