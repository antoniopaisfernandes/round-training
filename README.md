# TRAINING MANAGEMENT

## Requirements

* PHP >= 8.2 [https://www.php.net/]
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
* run `php artisan migrate --seed` (default credentials are u: `admin@roundtraining.com` p: `password`)
* run `npm install`
* run `npm run prod` to build the frontend assets
* bootstrap server with `php artisan serve`
