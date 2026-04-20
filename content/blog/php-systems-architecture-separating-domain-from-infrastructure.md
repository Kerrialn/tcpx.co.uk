---
title: "PHP Systems Architecture: Separating Domain Logic from Infrastructure"
slug: php-systems-architecture-separating-domain-from-infrastructure
date: 2026-04-07
description: "Why PHP systems that mix domain logic with infrastructure code become impossible to upgrade — and how to separate them using ports and adapters, repository patterns, domain services, and purpose-built tools like Migrator, Indoctrinate, and View Converter."
keywords: "PHP systems architecture, PHP domain logic, PHP clean architecture, PHP hexagonal architecture, PHP ports adapters, PHP infrastructure separation, PHP architect, View Converter PHP, Migrator PHP"
---

The most common structural problem in legacy PHP applications is not a specific bad pattern — it's the absence of any separation between what the application *does* and how it *does it*. Business rules live in controllers. Database queries appear in view templates. Emails are sent from inside domain methods. The result is a codebase where nothing can be tested in isolation, nothing can be replaced, and nothing can be upgraded without touching everything.

This article explains the architectural principle that fixes it — and the tools that automate the mechanical parts of getting there.

## Assess before you restructure: Migrator

Before any architectural work starts, you need to know what you're dealing with. [Migrator](https://github.com/Kerrialn/migrator) analyses the complexity of upgrading or migrating your project — checking your codebase to assess effort, highlighting potential challenges, dependencies, and necessary changes.

```bash
composer require kerrialn/migrator
vendor/bin/migrator analyse /path/to/your/project
```

Migrator's output tells you where the most complex coupling exists, which dependencies are blocking upgrades, and which areas of the codebase will need manual architectural intervention versus automated tooling. It shapes the priority order for the architectural work: tackle the highest-complexity, highest-risk areas first.

## What "domain logic" means

Domain logic is the code that encodes your business rules. If you stripped out all the databases, HTTP frameworks, email providers, and third-party APIs, the domain logic is what would remain — the pure rules about what your application does.

Examples of domain logic:
- Calculating an invoice total with applicable discounts
- Determining whether an order can be cancelled based on its status
- Validating that a subscription has sufficient credits for an operation

Examples of infrastructure:
- The SQL query that retrieves the order
- The HTTP controller that parses the request
- The SMTP call that sends the confirmation email
- The Stripe SDK call that processes the payment

Domain logic contains no `PDO`, no `Request`, no `Mailer`. It is pure PHP: value objects, entities, domain services, and domain events.

## Why mixing them is an upgrade blocker

When domain logic is mixed with infrastructure code, every upgrade touches everything. Want to move from Symfony 4 to Symfony 6? The domain logic that's embedded in Symfony-specific controllers, services, and forms has to change too. Want to move from raw MySQL to Doctrine? Every file containing both a query and a business rule needs touching.

Rector can automate PHP syntax upgrades. It cannot extract business logic from infrastructure — that's an architectural change, and it has to be done deliberately.

## The ports and adapters pattern

The cleanest model for this separation is Hexagonal Architecture (also known as Ports and Adapters). The core idea:

- **Domain** — pure business logic, no dependencies on frameworks or infrastructure
- **Ports** — interfaces the domain defines for things it needs (e.g. `OrderRepository`, `PaymentGateway`, `EmailNotifier`)
- **Adapters** — concrete implementations of those interfaces that talk to actual infrastructure (Doctrine, Stripe, Symfony Mailer)

```
[HTTP Controller] → [Application Service] → [Domain] → [Port Interface]
                                                              ↑
                                                    [Adapter: Doctrine]
                                                    [Adapter: Stripe]
                                                    [Adapter: SymfonyMailer]
```

The domain knows nothing about the adapters. The adapters know the domain's interfaces. The application service orchestrates them. The controller knows only the application service.

## A concrete PHP example

A legacy controller doing too much:

```php
class OrderController
{
    public function cancel(int $orderId): Response
    {
        $pdo = new PDO(getenv('DATABASE_URL'));
        $order = $pdo->query("SELECT * FROM orders WHERE id = $orderId")->fetch();

        if ($order['status'] !== 'pending') {
            return new Response('Cannot cancel', 400);
        }

        $pdo->exec("UPDATE orders SET status = 'cancelled' WHERE id = $orderId");

        mail($order['customer_email'], 'Order Cancelled', 'Your order has been cancelled.');

        return new Response('Cancelled');
    }
}
```

This controller is untestable, SQL-coupled, and impossible to upgrade without rewriting.

The same logic after domain separation:

```php
// Domain
final class Order
{
    public function cancel(): void
    {
        if (!$this->status->isPending()) {
            throw new CannotCancelOrderException($this->id);
        }
        $this->status = OrderStatus::Cancelled;
        $this->record(new OrderCancelled($this->id, $this->customerId));
    }
}

// Application Service
final class CancelOrder
{
    public function __construct(
        private OrderRepository $orders,
        private EventDispatcher $events,
    ) {}

    public function execute(CancelOrderCommand $command): void
    {
        $order = $this->orders->findById($command->orderId);
        $order->cancel();
        $this->orders->save($order);
        $this->events->dispatch(...$order->releaseEvents());
    }
}

// Controller (thin)
final class OrderController
{
    public function cancel(int $orderId, CancelOrder $useCase): Response
    {
        $useCase->execute(new CancelOrderCommand($orderId));
        return new Response('Cancelled');
    }
}
```

Now `Order::cancel()` can be unit tested without a database. The SQL is behind `OrderRepository`. The email is behind an event listener. The controller does nothing but translate HTTP to a command.

## How to get there incrementally

You can't rewrite a large legacy codebase all at once. The incremental path, with tooling at each layer:

**1. Assess with Migrator** — run `vendor/bin/migrator analyse` to map complexity and identify which bounded contexts are safest to tackle first.

**2. Identify a bounded context** — pick one area of the application (e.g. orders, subscriptions, invoicing) and apply the pattern there first.

**3. Extract the domain** — create plain PHP classes that hold the business rules; no framework dependencies. Rector can apply PHP 8 syntax upgrades to these immediately — clean domain classes are exactly what Rector works best on.

**4. Define repository interfaces** — what does the domain need from the database? Express it as an interface. Use [Indoctrinate](https://github.com/Kerrialn/indoctrinate) to synchronise the database schema with Doctrine expectations before writing entity mappings:

```bash
composer require kerrialn/indoctrinate
php bin/indoctrinate fix --dry
php bin/indoctrinate fix
```

Indoctrinate ensures the schema is in a state Doctrine can map cleanly — missing primary keys, type mismatches, and constraint gaps are fixed automatically before you invest time in entity configuration.

**5. Write the Doctrine adapter** — implement the repository interface; the domain never changes when you switch adapters.

**6. Modernise the presentation layer with View Converter** — legacy PHP views mixed with business logic are one of the most common sources of domain/infrastructure entanglement. [View Converter](https://github.com/Kerrialn/view-converter) automates the conversion of PHP view files to Twig:

```bash
composer require kerrialn/view-converter
php bin/view-converter php-to-twig /path/to/your/views
```

This removes the PHP-in-templates pattern that tends to contain raw SQL, embedded business logic, and direct superglobal access — all at once, without manual file-by-file rewriting.

**7. Thin down the controller** — once views are converted and domain logic is extracted, what remains in the controller is just HTTP translation. Move any remaining logic into an application service.

**8. Apply Rector to the result** — once the structure is clean, Rector's PHP 8 upgrades, type declarations, and deprecated API replacements apply safely and predictably to code that has clear boundaries.

## The full toolchain

| Layer | Tool | What it does |
|---|---|---|
| **Assessment** | Migrator | Analyses upgrade complexity, surfaces manual tasks |
| **Code** | Rector | Automated PHP 8 syntax and API transformations |
| **Database** | Indoctrinate | Syncs schema with Doctrine, fixes inconsistencies |
| **Views** | View Converter | Converts PHP view files to Twig automatically |

These tools work in sequence. Migrator tells you what you're facing. View Converter and Indoctrinate clear the infrastructure entanglement. Rector then upgrades the clean PHP that remains.

## The payoff

Once domain logic is separated from infrastructure:

- Domain classes can be unit tested in milliseconds, without a database
- Framework upgrades only affect adapters and controllers — the domain is untouched
- Rector can apply type upgrades and PHP 8 transformations to a clean, predictable structure
- View Converter has eliminated PHP-in-templates; Twig templates have no business logic to migrate
- Indoctrinate keeps the schema aligned with Doctrine as it evolves
- New developers understand the rules of the system without reading SQL
- The codebase can be handed over without a six-month knowledge-transfer project

If this separation is what your codebase is missing, the right starting point is an architecture review. [Get in touch](/contact) to discuss what a structured PHP systems architecture engagement looks like.
