---
title: "Why You Should Upgrade Your PHP System Before Reducing Your Dev Team"
slug: cto-guide-upgrading-php-before-reducing-dev-team
date: 2024-05-24
description: "Reducing development headcount to cut costs is tempting — but without a solid technical foundation, the remaining team will struggle. Here's why upgrading first makes the economics work."
keywords: "PHP codebase upgrade, reduce development costs, PHP modernisation ROI, AI developer productivity, legacy PHP cost, PHP technical debt ROI"
---

The pressure to reduce development costs is real. Post-COVID operational costs, energy price spikes, and a slump in growth markets have all tightened budgets. And now, with AI tooling advancing rapidly, there's a tempting case for cutting developer headcount.

The logic seems sound: AI makes developers more productive, so fewer developers can achieve the same output. Reduce the team, maintain delivery pace, protect margins.

This works — but only if your technical foundation supports it.

## The problem with applying this to legacy systems

Let's say you have a team of five developers, and you slim down to two. That's a 60% reduction in headcount. For the remaining two to simply maintain the *status quo*, each would need to be 150% more productive using AI tools. And the goal isn't to maintain the status quo — it's to improve on it.

There's a harder problem underneath this: AI has real limitations with complex, disorganised legacy systems. AI loses context quickly, and the messier, more inconsistent, and more buggy your codebase, the less useful it becomes. It gets lost in the same rabbit holes a junior developer would.

On the other hand, if you have a well-structured, SOLID-compliant, standards-enforced codebase, AI can help resolve problems significantly more effectively — because the scope and context is small and clear.

## What actually happens when you reduce headcount too early

Reducing your team before the system is ready leads to:

- High developer turnover — few developers want to work on a difficult legacy codebase under pressure with a small team
- Low morale and burnout for those who stay
- Excessive time spent on crashes and bugs rather than progress
- Progressively slower delivery as technical debt compounds faster than it's addressed

## The right sequence

Spend six months upgrading your project *before* reducing headcount. Use that time to:

1. **Assess the current state** — run [Migrator](https://github.com/Kerrialn/migrator) to produce a structured analysis of upgrade complexity and technical risk
2. **Automate the quality floor** — integrate PHPStan, Rector, and ECS into CI so every PR is gate-checked for quality
3. **Migrate database access** — use [Indoctrinate](https://github.com/Kerrialn/indoctrinate) to systematically convert raw queries and string-based SQL to type-safe Doctrine ORM
4. **Remove dead code and upgrade PHP** — use Rector's dead code and PHP version sets to modernise incrementally

After this investment, you will have:

- A codebase small enough for AI tools to reason about effectively
- A quality enforcement pipeline that makes it hard to introduce new debt
- Fewer moving parts overall — meaning less maintenance burden per developer
- A system that developers actually want to work on, making retention and hiring easier

## The economics

The cost of six months of focused technical improvement is typically a fraction of the cost of 12+ months of high turnover, productivity losses, and technical debt accumulation.

Reducing your development team is feasible and sometimes the right call. But plan, prepare, and then proceed — in that order.
