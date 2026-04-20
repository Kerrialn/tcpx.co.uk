---
title: "Why Doctrine ArrayCollections Beat Arrays for PHP Domain Objects"
slug: doctrine-arraycollection-over-arrays
date: 2024-04-05
description: "Why Doctrine's ArrayCollection is a better choice than plain arrays when working with typed domain objects in PHP — and how it enables filtering, mapping, and finding without type gymnastics."
keywords: "Doctrine ArrayCollection, PHP typed collections, Doctrine Collections, PHP domain objects, PHP array alternatives, Symfony entities"
---

Plain PHP arrays have significant limitations when it comes to type safety. Nothing prevents a single array from containing an object, a string, an integer, and a boolean simultaneously:

```php
$array = [
    new Quote(quote: 'That\'s all any of us are: amateurs.', author: 'Charlie Chaplin'),
    'some string',
    215,
    false,
];
```

This is fantastic for transferring heterogeneous data, but troublesome when iterating. You have to add type checks everywhere:

```php
foreach ($array as $data) {
    if ($data instanceof Quote) {
        // do something
    } elseif (is_string($data)) {
        // do something
    } elseif (is_int($data)) {
        // do something
    }
}
```

Even with homogeneous arrays it gets messy. A common pattern:

```php
$results = [];
foreach ($items as $item) {
    if (/** some check */) {
        $results[] = $item['some_key'];
    }
}
```

Two problems: you're accessing an array key without any guarantee it exists or what type it holds, and you're building a second array to filter the first.

## ArrayCollection solves both problems

[Doctrine Collections](https://packagist.org/packages/doctrine/collections) provides `ArrayCollection` — a typed, iterable collection with a rich API for filtering, mapping, sorting, and reducing.

```php
$quotes = new ArrayCollection([
    new Quote(quote: 'That\'s all any of us are: amateurs.', author: 'Charlie Chaplin'),
    new Quote(quote: 'Pull back the curtain on your process.', author: 'Ann Friedman'),
    new Quote(quote: 'Wealth of information creates a poverty of attention.', author: 'Herbert Simon'),
]);

$chaplinQuotes = $quotes->filter(
    fn(Quote $quote) => str_contains($quote->getAuthor(), 'Charlie Chaplin')
);
```

In one line, with full type safety — no loop, no accumulator array, no string key access.

## On Doctrine entities

The more common usage is as a property on an entity:

```php
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /** @return Collection<int, Tag> */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }
}
```

Every call to `getTags()` returns a `Collection<int, Tag>` — PHPStan knows what's in it, your IDE knows what's in it, and no runtime type check is needed anywhere downstream.

## Combining multiple collections

`ArrayCollection` composes well. You can merge two typed collections and then query across both:

```php
public function getPackageByName(string $name): null|Package
{
    $packages = new ArrayCollection([
        ...$this->getRequires(),
        ...$this->getDevRequires(),
    ]);

    return $packages->findFirst(
        fn(int $key, Package $package) => $name === $package->getName()
    );
}
```

No loops, no null checks mid-array, no `array_filter` — just a typed collection and a typed callback.

## When to still use plain arrays

Arrays are fine for:

- Returning simple lists of scalars
- Passing data to Twig templates
- Constructing query parameters

Reach for `ArrayCollection` when you need to iterate over domain objects and perform any computation, filtering, or lookup. The type safety pays off immediately — PHPStan can reason over the collection, and your code becomes self-documenting.
