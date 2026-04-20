---
title: "The Zero-Legacy Approach: Simplifying PHP Architecture"
slug: zero-legacy-approach-simplifying-php-architecture
date: 2024-04-23
description: "A zero-legacy development approach means continuously reducing technical debt before it becomes load-bearing. Here's why over-complex systems are becoming unaffordable — and what to do about it."
keywords: "zero legacy PHP, PHP architecture simplification, PHP technical debt, legacy PHP modernisation, simplified systems architecture, PHP consultancy"
---

In recent years, applications have become bloated and extremely complex. With the advent of JavaScript frontend frameworks, teams have essentially doubled or tripled the workload by creating two or more isolated systems for what is conceptually one application.

The assumption driving many layoffs since 2023 has been that AI will replace the need for development resources. Applying this logic to new, clean systems is reasonable. Applying it to legacy systems is not.

Legacy systems are complex, interconnected, and poorly documented. AI can help a skilled developer be *more productive* on such a system — it cannot replace the contextual understanding required to navigate it. For the largest legacy systems, that context is even beyond most humans. The layoffs have begun; the AI capability to plug that gap hasn't arrived. So what's the solution?

Dramatically simplify systems.

## What zero-legacy actually means

Zero-legacy is a development approach focused on continuously reducing technical debt before it becomes embedded.

Problems in a codebase don't stay isolated — like an infection, they spread. A bug or architectural misdesign becomes a dependency of another piece of code. Once that happens, and as more features depend on it, it becomes progressively harder to fix.

A concrete example: a wrongly typed field in a database. You build your application casting it to the correct type wherever it's used. After two years, that cast appears in 500 places. What was once a ten-minute fix is now a significant refactor, because 500 places depend on a design error.

Zero-legacy means fixing that *before* it becomes load-bearing — not in a single heroic refactoring sprint, but continuously, as part of normal development. Specifically:

- Fix issues in your code **before** submitting a pull request, so problems don't make it into the codebase in the first place
- Prioritise removal of unused code alongside feature delivery — use Rector's `withDeadCodeLevel()` as a regular step
- Run automated tooling ([PHPStan](https://phpstan.org/), [Rector](https://getrector.com/), [ECS](https://github.com/easy-coding-standard/easy-coding-standard)) on every PR so the quality floor never drops

## Consolidate instead of adding more

Instead of spending money on maintaining separate frontend and backend systems, use one consolidated system. Hotwire's Turbo and Stimulus, or lightweight packages like HTMX, deliver modern interaction without the architectural overhead of a separate SPA.

More importantly, shift thinking from *deliver all requested features* to *how much effort is this feature worth*. Features that are disproportionately expensive to implement relative to their value are a signal to simplify the scope — not to simply execute regardless of cost. The era of the unchecked backlog is becoming too expensive to sustain.

## The compounding benefit

Clean, well-structured systems are the ones where AI tooling actually helps. The smaller and better-defined the scope, the more effectively an AI can suggest, generate, or review code. A zero-legacy approach isn't just about avoiding problems — it's about making your entire development process faster and cheaper over time.

The shift toward simpler, more maintainable architecture is already underway at the top of the market. The teams that adopt it now will have a structural advantage as the broader industry catches up.
