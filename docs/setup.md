# Setup and development

-   [Setup and development](#setup-and-development)
    -   [First-time setup](#first-time-setup)
    -   [Installation](#installation)
    -   [Dev server](#dev-server)
        -   [Developing with the production API](#developing-with-the-production-api)
    -   [Generators](#generators)
    -   [Aliases](#aliases)
    -   [Globals](#globals)
        -   [Base components](#base-components)

## First-time setup

Make sure you have the following installed:

-   [Node](https://nodejs.org/en/) (at least the latest LTS)
-   [NPM](https://www.npmjs.com/) ( atleast the LTS)
-   [Composer](https://getcomposer.org/) ( atleast the LTS)

Then update the following files to suit your application:

-   `.env`

## Installation

```bash
# Install dependencies from package.json
npm install
# Install dependencies from composer.json
composer install
```

## Dev server

```bash
# Launch the dev server
php artisan serve
```

## Generators

This project includes generators to speed up common development tasks. Commands include:

```bash
TODO
```

## Aliases

-   `@src` resolves at resources/js
-   `@views` resolves at resources/js/views
-   `@layout` resolves at resources/js/layout
-   `@api` resolves at resources/js/api

## Globals

### Base components for vue

[Base components](https://vuejs.org/v2/style-guide/#Base-component-names-strongly-recommended) (a.k.a. presentational, dumb, or pure components) that apply app-specific styling and conventions should all begin with the `_base-` prefix. Since these components are typically used in place of raw HTML element (and thus used as frequently), they're automatically globally registered for convenience. This means you don't have to import and locally register them to use them in templates.
