---
title: "Breaking Free from Tech Team Dependency: A Business Owner's Guide"
slug: breaking-free-from-tech-team-dependency
date: 2024-05-21
description: "If your business is held hostage by a single developer or a dysfunctional tech team, there is a way out. Here is a structured approach to analysing, prioritising, and incrementally recovering your application."
keywords: "tech team dependency, legacy codebase rescue, PHP codebase analysis, software modernisation, PHP consultancy, legacy software management"
---

Are you frustrated by how slow, problematic, and stressful delivering new features for your application has become?

You may even have started to believe this is normal — that there is supposed to be a constant battle between management and tech to get anything shipped. It isn't. And it isn't helpful to frame the problem as a war between departments in a company that's supposed to be working toward the same objective.

That said, there is an increasing pattern of companies that end up effectively held hostage by their tech team.

## The "oracle" dynamic

Typically there will be one person — the *oracle* — who has been in the company's tech department for a long time, perhaps from the beginning. Everyone defers to them for knowledge about the system, yet nobody else seems to understand what's actually going on.

As the company has grown and changed, this person hasn't grown with it. They've entrenched their position by making themselves seem indispensable. How? By:

- Withholding knowledge from incoming developers
- Writing unnecessarily complex or disorganised code that others can't understand
- Limiting technology choices to tools they're personally comfortable with, regardless of business need

You may already know this is happening and feel there's nothing you can do. There is.

## The oracle was a symptom, not the cause

Removing the oracle won't solve the problem, because by that point the damage is already done. The knowledge is missing, the architecture is unclear, and the codebase reflects years of individual decisions made without documentation.

This situation typically stems from broader cultural issues — but on the technical side, the solution is always the same: get the knowledge out of one person's head and into the codebase.

## Quantify the problem first

Before taking any action, admit you have a problem and measure it. The question to answer is: how structurally sound is the application?

Run [Migrator](https://github.com/Kerrialn/migrator) to get a concrete picture:

```bash
composer require kerrialn/migrator
vendor/bin/migrator analyse
```

This gives you an analysis report covering PHP compatibility, dependency state, code complexity, and upgrade risk. It will tell you whether the application is worth rescuing or whether it's more cost-effective to start fresh with a clean architecture and proper standards.

If the analysis shows the application is terminal, cutting losses and rebuilding is now a data-driven decision rather than a panic reaction. If it's salvageable, you have a concrete list of what needs to be done and in what order.

## Set realistic priorities

An unrealistic priority at this stage would be feature delivery. Trying to ship new features while the foundation is broken is like running on a broken leg — theoretically possible, but likely to create further complications.

A realistic goal: stay functionally identical for the next several months, but emerge with a highly organised, efficiently structured, standards-compliant system that any developer can work on and would want to work on.

From the analysis report, identify the full list of required improvements and then choose the smallest, easiest one that can be done now. Do it. Merge it. Repeat.

## The knowledge shift

As small fixes accumulate and the application becomes more legible, something interesting happens. Knowledge that was locked in one person starts living in the codebase — in the structure, the tests, the documentation, the automated tooling.

Other developers begin contributing and engaging, because they can actually understand the system. The bottleneck dissolves — not because the oracle left, but because the application no longer requires an oracle to navigate it.

The journey is frustrating and occasionally demoralising. But the outcome — a collaborative, maintainable technical environment where knowledge is shared and any capable developer can contribute — is worth every step of it.

Start with the smallest possible improvement. Merge it. Repeat.
