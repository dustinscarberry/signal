[![Build Status](https://travis-ci.com/dustinscarberry/signal.svg?branch=master)](https://travis-ci.org/dustinscarberry/signal)

# Signal

Signal is a open source system status dashboard inspired by Cachet and others, built on Symfony and React.

## Tech ##

* [React] - JS framework for responsive apps
* [Symfony 4.3] - PHP MVC Framework
* [composer] - PHP Dependency Manager
* [npm] - JS Dependency Manager
* [jQuery] - More JS stuff

## Requirements ##

* [PHP 7.2.0 or higher](https://www.php.net/)
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

Nginx Basic Config

```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /etc/nginx/ssl/cert.cer;
    ssl_certificate_key /etc/nginx/ssl/cert.key;
    server_name __;
    root /var/www/signal/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
       fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
       fastcgi_param DOCUMENT_ROOT $realpath_root;
       internal;
   }

   location ~ \.php$ {
     return 404;
   }
}
```

## Upgrades

1. Get updates from repo.

```sh
git pull
```

2. Run database migrations.

```sh
php bin/console doctrine:migrations:migrate
```

> WARNING: Do not install fixtures unless needed. Failure to do so will result in total loss of data!

3. Install new fixtures (if needed) - None currently

4. Clear cache.

```sh
rm -rf var/cache/*
```
