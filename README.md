## Full text search in Laravel

The following documentation is based on my [Laravel Text Search using Laravel Scout]() tutorial where I’ll show you how you can easily implement a search bar inside your Laravel project. We are going to implement Laravel Scout and Algolia Driver for this tutorial. [Laravel Scout](https://laravel.com/docs/8.x/scout#:~:text=Laravel%20Scout%20provides%20a%20simple,with%20Algolia%20and%20MeiliSearch%20drivers) is a simple driver that will add full-text search to your Eloquent models. Algolia Driver is an API tool that will deliver real time results from requests. <br> <br>
•	Author: Code With Dary <br>
•	Twitter: [@codewithdary](https://twitter.com/codewithdary) <br>
•	Instagram: [@codewithdary](https://www.instagram.com/codewithdary/) <br>

## Usage <br>
Setup the Laravel 8 complete blog repository <br>
```
git clone git@github.com:codewithdary/laravel-8-complete-blog.git
cd laravel-8-complete-blog
composer install
cp .env.example .env 
php artisan key:generate
php artisan cache:clear && php artisan config:clear 
php artisan serve 
```

## Database Setup <br>

We are going to use a composer package that will search text through your Eloquent models. Let’s set up our database.

First, make sure that your database credentials are setup in the .env file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelblog
DB_USERNAME=root
DB_PASSWORD=
```

Next up, we need to create the database which will be grabbed from the ```DB_DATABASE``` environment variable.
```
mysql;
create database laravelblog;
exit;
```
The ```Laravel 8 complete blog``` has one very important migration which is needed for this tutorial, and that’s the ```/database/migrations/2021_02_22_174718_posts.php``` file. We are going to search through our posts so we need to make sure that we migrate our migrations.
```
php artisan migrate
```

## Pull in Laravel Scout & Algolia
Just like any other package in Laravel, it’s very simple to pull it in because we got Composer! Inside the CLI, you need to perform the following command
```
composer require laravel/scout
```

This will pull in the necessary Laravel Scout packages. It will also create the following configuration file ```/config/scout.php```, which we actually can’t access immediately. We need to make sure that we publish it from the vendor directory through Artisan.

Inside the CLI, you need to perform 
```
php artisan vendor:publish –provider=”Laravel\Scout\ScoutServiceProvider”
```

Before we can interact with our search client, we need to make sure that we pull in Algolia through composer.
```
composer require algolia/algoliasearch-client-php
```

In order to interact with Algolia, you need to make sure that you create an account on their [official website]( https://www.algolia.com/). You can register and use their free trial which lasts 14 days.

A quick tip of me is to open the ```/config/scout.php``` file and search through the file for something that might stick out. Well I can help you with that! You can see that it refers to .env credentials, which we actually haven’t setup. In my head, an alarm goes off which tells me that I somehow need to find API keys on Algolia’s site.

Navigate to the dashboard of Algolia, and click on the “API KEYS” menu item. Right here, you need to copy the ```Application ID```, which needs to be set equal to the ```ALGOLIA_APP_ID``` variable inside the .env file, and the ```Admin Api Key```, which needs to be set equal to the ```ALGOLIA_SECRET_ID``` variable
```
ALGOLIA_APP_ID=""
ALGOLIA_SECRET_ID=""
```
