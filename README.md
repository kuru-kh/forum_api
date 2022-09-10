# XYZ Forum
Build using Laravel 8 framework.
Require PHP 7.3 or higher

To use the system : 
 - create .env from .env.example 
 - run `composer install` 
 - update your database name and credentials in `.env` file 
 - `php artisan key:generate`
 - `php artisan migrate --seed`
 - update `APP_DEBUG` to ` false` and `APP_ENV` to `production` in `.env` file 
 - `php artisan serve`


