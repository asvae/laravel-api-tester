# Laravel Api Tester

[![Integration tests](https://travis-ci.org/asvae/laravel-spa-boilerplate.svg)](https://travis-ci.org/asvae/laravel-spa-boilerplate)
[![Unit tests](https://travis-ci.org/asvae/laravel-api-tester.svg)](https://travis-ci.org/asvae/laravel-api-tester)

![Interface](http://i.imgur.com/H7fkU3r.png) 

## Live demo
Try it out: [laravel-api-tester.asva.by](http://laravel-api-tester.asva.by/)

<!--- ## Disclaimer

Image and demo are from `development` branch. It's definitely more usable and quite stable, compared to master release. Give it a try:
```
composer require asvae/laravel-api-tester:dev-development
``` --->

## Installation

Require this package with composer:

```
composer require asvae/laravel-api-tester
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

```
Asvae\ApiTester\ServiceProvider::class,
```

That's it. Go to `[your site]/api-tester` and start testing routes. 

## Config

By default, the package is bound to `APP_DEBUG` `.env` value. But you can easily override it. Just publish config:

```
php artisan vendor:publish --provider="Asvae\ApiTester\ServiceProvider"
```

And edit `config/api-tester.php` as you please.

## Features
* Display routes for your application.
* Live search and sortable columns.
* Filter out routes in [config](config/api-tester.php).
* CSRF token is handled for you.
* Define request body in [JSON editor](https://github.com/josdejong/jsoneditor).
* Preview response depending on type (html or json).
* Clean and intuitive interface.
* Lightweight and no dependencies (except on laravel).

## Powered By
* [Vue.js](https://vuejs.org/)
* [Bulma](http://bulma.io/)

## Feedback
Don't hesitate to raise an issue if something doesn't work or you have a feature request. You're welcome to.

## Timeline
* 1.0.10 (19.07.2016) - add filters (thanks [greabock](https://github.com/greabock)).

## Tests
We do unit tests as well as integration tests, which is rare for laravel libraries. Check badges on the top for details

## Licence
MIT
