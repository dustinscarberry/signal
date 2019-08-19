[![Build Status](https://travis-ci.com/dustinscarberry/signal.svg?branch=master)](https://travis-ci.org/dustinscarberry/signal)

# Signal

Signal is a open source system status dashboard inspired by Cachet and others, built on Symfony and React.

## Tech ##

* [React] - JS framework for responsive apps
* [Symfony 4.2] - PHP MVC Framework
* [composer] - PHP Dependency Manager
* [npm] - JS Dependency Manager
* [jQuery] - More JS stuff

## Requirements ##

* [PHP 7.3.0 or higher](https://www.php.net/)
* [MariaDB 10.0+](https://mariadb.org/)
* [Composer](https://getcomposer.org/)
* [NodeJS](https://nodejs.org/)

## Documentation ##

The [docs folder](docs/) provides detailed guides for the many features of signal.

## Installation

Clone repository.

```sh
$ cd /var/www
$ git clone https:\\github.com\dustinscarberry\signal
```

Install dependencies.

```sh
$ cd signal
$ composer install
$ npm i
```

Create and configure .env.local at root of project directory.

```php
#required properties
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databasename
MAILER_URL=emailConnectionString - https://symfony.com/doc/current/email.html#configuration

#optional properties
EXCHANGE_CALENDAR_HOST=outlook.sample.com
EXCHANGE_CALENDAR_USERNAME=username@sample.com
EXCHANGE_CALENDAR_PASSWORD=password
EXCHANGE_CALENDAR_VERSION=2013
```

Clear cache.

> This will need to be ran before / after any upgrades to clear the production app cache.

```sh
$ rm -rf /var/www/signal/var/*
```

Run migrations.

```sh
$ php bin/console doctrine:migrations:migrate
```

Load basic data.
```sh
$ php bin/console doctrine:fixtures:load
```

Run server (if needed)

//add nginx sample config here
