---
title: "Doctrine ORM QueryBuilder: Writing Reusable, Type-Safe Queries"
slug: doctrine-orm-querybuilder-reusable-queries
date: 2024-03-28
description: "How to use the Doctrine ORM QueryBuilder to write short, efficient, reusable SQL queries with DTOs — and why PHP-based queries can be analysed with PHPStan and Rector in a way raw SQL never can."
keywords: "Doctrine QueryBuilder, Doctrine ORM, Symfony repository pattern, PHP DTO, reusable queries, PHPStan Doctrine, Symfony database"
---

One of the genuine joys of using Symfony is the Doctrine ORM integration. It makes constructing complex data access logic genuinely manageable — if you know the patterns.

There are three questions worth answering when thinking about database querying in a Symfony repository:

1. Can we write short, efficient queries?
2. Can we reuse query methods and compose them together?
3. Can we run static analysis tools on our query code?

The answer to all three is yes. Here's how.

## The problem: untyped form data

If you submit a form in a controller and use the submitted data to query the database, you typically end up with something like this:

```php
#[Route('/some/route', name: 'some_route')]
public function index(Request $request): Response
{
    $emailForm->handleRequest($request);
    if ($emailForm->isSubmitted() && $emailForm->isValid()) {
        $emailAddress = (string) $emailForm->get('email')->getData();
        $email = $this->emailRepository->findByEmailAddress($emailAddress);
    }
}
```

The problem: `getData()` returns `mixed`, so you have to cast it. This is unpredictable and signals a type gap at the boundary between your form and your repository.

A better approach: use a DTO as the form's `data_class`, so the controller receives a typed object instead of a raw mixed value.

## Using a DTO as the form data class

Define a DTO with typed properties for each form field:

```php
class EventFilterDto
{
    private ?string $keyword = null;
    private null|EventFilterDateRangeEnum $period = null;
    private null|Category $category = null;
    private null|Country $country = null;

    public function getKeyword(): ?string { return $this->keyword; }
    public function setKeyword(?string $keyword): void { $this->keyword = $keyword; }

    public function getPeriod(): null|EventFilterDateRangeEnum { return $this->period; }
    public function setPeriod(null|EventFilterDateRangeEnum $period): void { $this->period = $period; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): void { $this->category = $category; }

    public function getCountry(): ?Country { return $this->country; }
    public function setCountry(?Country $country): void { $this->country = $country; }
}
```

Now your controller works with a typed `EventFilterDto` object — no casting, no mixed:

```php
#[Route(path: '/', name: 'events')]
public function index(Request $request): Response
{
    $eventFilterDto = new EventFilterDto();
    $eventFilter = $this->createForm(EventFilterType::class, $eventFilterDto);
    $events = $this->eventRepository->findByFilter(eventFilterDto: $eventFilterDto, isQuery: true);

    $eventFilter->handleRequest($request);
    if ($eventFilter->isSubmitted() && $eventFilter->isValid()) {
        $events = $this->eventRepository->findByFilter(eventFilterDto: $eventFilterDto, isQuery: true);

        return $this->render('events/index.html.twig', [
            'eventFilter' => $eventFilter,
            'events' => $events,
        ]);
    }

    return $this->render('events/index.html.twig', [
        'eventFilter' => $eventFilter,
        'events' => $events,
    ]);
}
```

## Composable QueryBuilder methods

The real power comes in the repository. The `findByFilter` method takes the DTO and delegates to smaller, reusable methods:

```php
/**
 * @return Query|array<int, Event>
 */
public function findByFilter(EventFilterDto $eventFilterDto, bool $isQuery = false): Query|array
{
    $qb = $this->createQueryBuilder('event');

    if ($eventFilterDto->getPeriod() instanceof EventFilterDateRangeEnum) {
        $this->findByPeriod(period: $eventFilterDto->getPeriod(), qb: $qb);
    }

    if ($eventFilterDto->getCategory() instanceof Category) {
        $this->findByCategory(category: $eventFilterDto->getCategory(), qb: $qb);
    }

    if (!empty($eventFilterDto->getKeyword())) {
        $this->findByTitle(keyword: $eventFilterDto->getKeyword(), qb: $qb);
    }

    $qb->andWhere($qb->expr()->eq('event.isPrivate', ':false'))
       ->setParameter('false', false)
       ->orderBy('event.startAt', Order::Ascending->value);

    return $isQuery ? $qb->getQuery() : $qb->getQuery()->getResult();
}
```

Note the `instanceof` checks rather than null checks. Checking for the presence of what you *want* is clearer and more reliable than checking for the absence of what you don't want.

Each sub-method (e.g. `findByTitle`) works both standalone and as part of a composed query:

```php
/**
 * @return Query|array<int, Event>
 */
public function findByTitle(string $keyword, ?QueryBuilder $qb = null, bool $isQuery = false): Query|array
{
    if (!$qb instanceof QueryBuilder) {
        $qb = $this->createQueryBuilder('event');
    }

    $qb->andWhere($qb->expr()->like($qb->expr()->lower('event.title'), ':title'))
       ->setParameter('title', '%' . strtolower($keyword) . '%');

    $qb->leftJoin('event.eventGroup', 'event_group')
       ->orWhere($qb->expr()->like($qb->expr()->lower('event_group.name'), ':name'))
       ->setParameter('name', '%' . strtolower($keyword) . '%');

    return $isQuery ? $qb->getQuery() : $qb->getQuery()->getResult();
}
```

If no `QueryBuilder` is passed, the method creates its own — so it works standalone. If one is passed, it adds to it — so it composes.

## The static analysis advantage

Because you're writing PHP rather than raw SQL strings, PHPStan, ECS, and Rector can all run over your query code. Type errors in your repository methods get caught at analysis time, not at runtime. Deprecated patterns get upgraded automatically. Coding standards get enforced.

You end up with short, efficient, reusable, composable, statically-analysed queries. That's a significant improvement over raw SQL in strings or monolithic query methods that can't be independently reused.
