# Signal

Signal is a open source system status dashboard inspired by Cachet and others, built on Symfony and React.

## Tech ##

* [Symfony 7.4] - PHP MVC Framework
* [Composer] - PHP Package Manager
* [Yarn] - JS Package Manager
* [React] - JS framework for responsive apps

## Requirements ##

* [PHP 8.4 or higher](https://www.php.net/)
* [MariaDB 10.0+](https://mariadb.org/)
* [Composer](https://getcomposer.org/)
* [Yarn](https://yarnpkg.com/)

## Documentation ##

The [docs folder](docs/) provides detailed guides for the many features of signal.

## Development

1. Clone repository

```sh
$ cd /var/www
$ git clone git@github.com:dustinscarberry/signal.git
```

2. Install dependencies

```sh
$ cd signal
$ composer i --no-scripts
$ yarn install --immutable
```

3. Build dependencies

```sh
$ yarn dev
```

4. Create and configure .env.local at root of project directory

```php
# required properties
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databasename
MAILER_URL=emailConnectionString - https://symfony.com/doc/current/email.html#configuration
```

5. Run migrations (from server)

```sh
$ php bin/console doctrine:migrations:migrate
```

6. Load basic data
```sh
$ php bin/console doctrine:fixtures:load
```

## Installation (Standard)

1. Clone repository

```sh
$ cd /var/www
$ git clone git@github.com:dustinscarberry/signal.git
```

2. Install dependencies

```sh
$ cd signal
$ composer i --no-scripts --no-dev
$ yarn install --immutable
```

3. Build dependencies

```sh
$ yarn prod
```

4. Create and configure .env.local at root of project directory

```php
# required properties
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databasename
MAILER_URL=emailConnectionString - https://symfony.com/doc/current/email.html#configuration
```

5. Run migrations (from VM)

```sh
$ php bin/console doctrine:migrations:migrate
```

6. Load basic data
```sh
$ php bin/console doctrine:fixtures:load
```

7. Start server (nginx, if needed)

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
      fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
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

## Installation (Docker)

1. Clone repository

```sh
$ cd /var/docker
$ git clone git@github.com:dustinscarberry/signal.git
```

2. Create and configure .env.local at root of project directory

```php
# required properties
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databasename
MAILER_URL=emailConnectionString - https://symfony.com/doc/current/email.html#configuration
```

3. Create docker-compose.override.yml and modify ports to your environment
```sh
$ cd signal
$ cp docker-compose.override.example.yml docker-compose.override.yml
```

4. Run docker container

```sh
$ docker compose up -d --build
```

5. Start server (nginx, if needed)

Nginx Basic Proxy Config

```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /etc/nginx/ssl/cert.cer;
    ssl_certificate_key /etc/nginx/ssl/cert.key;
    server_name __;

    location / {
        proxy_pass http://127.0.0.1:8001;
        proxy_redirect off;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Host $server_name;
    }
}
```

## Upgrades (Standard)

1. Get updates from repo

```sh
git pull
```

2. Run database migrations

```sh
php bin/console doctrine:migrations:migrate
```

3. Clear cache

```sh
rm -rf var/cache/*
```