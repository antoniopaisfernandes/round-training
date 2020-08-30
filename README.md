# GESTÃO DE FORMAÇÃO

## Requirements

* PHP >= 7.2 [https://www.php.net/]
* MariaDB/MySQL [https://downloads.mariadb.org/]
* Composer [https://getcomposer.org/]
* Node [https://nodejs.org/]

## Install

After installing all requirements
* MariaDB
    * create a schema (eg: `round-training`)
    * create a user and assign full permissions to that schema
* Make sure `composer`, `php` and `node` are in the path

Goto the project folder:

* run `composer create-project`
* edit your `.env` file with correct database settings
* run `php artisan migrate --seed` (default credentials are u: `admin@roundtraining.pt` p: `password`)
* run `npm install`
* run `npm run prod` to build the frontend assets
* bootstrap server with `php artisan serve`




## Packages to check (delete this section afterwards)

https://github.com/spatie/period
https://github.com/lukeraymonddowning/poser
https://github.com/spatie/laravel-activitylog
https://github.com/fico7489/laravel-eloquent-join
https://github.com/spatie/laravel-schemaless-attributes

https://github.com/LinusBorg/portal-vue
