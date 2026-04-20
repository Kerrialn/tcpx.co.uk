---
title: "Symfony Asset Mapper (importmap) Over Encore: Why and How"
slug: symfony-asset-mapper-over-encore
date: 2024-07-24
description: "Why Symfony's Asset Mapper is a better choice than Encore for most projects — no Node.js, faster compilation, native ES module support, and automatic cache busting."
keywords: "Symfony Asset Mapper, importmap Symfony, Symfony Encore alternative, Symfony no Node.js, Symfony assets, asset-map compile, Symfony importmap"
---

Symfony provides two tools for asset management: the Asset Mapper (importmap) and Symfony Encore. Both work, but for most projects the Asset Mapper is the better choice. Here's why — and how to use it.

## Why Asset Mapper over Encore?

**No Node.js required.** The Asset Mapper doesn't need Node.js or NPM, which removes an entire dependency tree from your development environment and eliminates the conflicts and versioning issues that come with it.

**Minimal configuration.** Asset Mapper is designed to work with almost no setup. No `webpack.config.js`, no build pipeline to maintain, no Babel configuration to manage.

**Faster compilation.** `bin/console asset-map:compile` is significantly faster and less resource-intensive than a Webpack build. This adds up in CI pipelines and during local development.

**Native browser support with automatic cache busting.** The compiled output is suffixed with a unique hash on each compile, so browsers always receive the latest assets without any manual versioning. The browser handles module imports natively via ES module support.

## Setting up Asset Mapper

### Step 1: Install

```bash
composer require symfony/asset-mapper
```

### Step 2: Configure

When installed via Symfony Flex, a config file is created automatically. If you need to create it manually, add `config/packages/asset_mapper.yaml`:

```yaml
framework:
    asset_mapper:
        paths:
            - assets/
```

### Step 3: Add your assets

Place JavaScript and CSS files in the `assets/` directory. Your `assets/app.js` is the entry point:

```javascript
// assets/app.js
import './bootstrap.js';
import './styles/app.css';
```

For CSS imports within your stylesheet, always use the `url()` function — this ensures Asset Mapper uses the hashed filename correctly:

```css
/* assets/styles/app.css */
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
@import url("./some-custom-styles.css");
```

Without `url()`, the compiler will attempt to load the unhashed filename, which won't exist after compilation.

### Step 4: Add external packages via importmap

To add a JavaScript package without NPM:

```bash
bin/console importmap:require htmx.org
```

Then import it in your `app.js`:

```javascript
import 'htmx.org';
```

### Step 5: Compile for production

```bash
bin/console asset-map:compile
```

### Step 6: Include in Twig templates

```twig
{% block javascripts %}
    {% block importmap %}{{ importmap('app') }}{% endblock %}
{% endblock %}
```

The `importmap()` function generates the appropriate `<script type="importmap">` tag and imports for the browser.

## In development

In development mode, assets are served dynamically from the `assets/` directory — no compilation step needed. Compilation is only required for production.

The combination of no Node.js, fast compilation, and native browser behaviour makes Asset Mapper the right default for Symfony projects. Encore is still the better choice if you need advanced Webpack features like code splitting or complex loaders — but for the majority of projects, those aren't needed.
