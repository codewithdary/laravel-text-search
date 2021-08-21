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

We are going to use a composer package that will search text through your Eloquent models. Make sure that your database credentials are setup in the .env file
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

## Marking your model for indexing

The next setup is marking our model for indexing. This can be done through the Searchable trait that we need to add inside the desired model (Post model in our case). 

Open the ```/app/Models/Post.php``` file and add the Searchable trait inside the Post class.
```ruby
use HasFactory, Sluggable, Searchable;
```

Also, make sure that you pull in the Searchable class inside the use statement above the class.
```ruby
use Laravel\Scout\Searchable;
```

The Searchable trait has tons of methods that you can use and it’s actually too much to cover them inside the tutorial, if you are interested in them, you can navigate to the ```/vendor/Laravel/scout/Searchable.php``` file. 

It’s optional to define a method inside your model called searchableAs, which will define the model you want to search through. I usually add it to prevent misconfusion.

```ruby
public function searchableAs()
{
    return 'posts';
}
```

## Creating our Search Controller
You can add the search query inside the PostsController but I recommend you to create a separate Controller which will do the searching for you.
```
php artisan make:controller SearchController
```

In here, we need to make sure that we create a new function which will do the check if the post exists, and returns it back to the view.
```ruby
public function query(Request $request)
{
    if($request->has('search')) {
        $posts = Post::search($request->search)->get();
    } else {
        $posts = Post::get();
    }

    return view('search.index', [
        'posts' => $posts
    ]);
}
```

We are returning a view right here which does not exist, make sure that you create a folder called ```search``` with an ```index.blade.php``` file inside the ```/resources/views``` folder.

We can define the route as well inside the ```/routes/web.php``` file
```ruby
Route::get('/search/query', [SearchController::class, 'query']);
```

## Adding our frontend!
The last step is to make sure that we add a input fields and submit button on our frontend where users can add a post name that they want to search through. Below our session check inside the ```/resources/views/blog/index.blade.php```, let’s add:
```ruby
<div class="pt-15 w-4/5 m-auto">
    <form action="/search/query" method="GET">
        @csrf

        <input
            type="text"
            name="search"
            placeholder="Search..."
            class="pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium">

        <button
            type="submit"
            class="bg-green-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
            Submit
        </button>
    </form>
</div>
```

Obviously, the view we want to navigate users to does not exist yet, so you can copy paste the entire ```/resources/views/blog/index.blade.php``` file inside the ```/resources/views/search/index.blade.php``` file, and change the ```<h1>``` to:
```ruby
<h1 class="text-6xl">
    Search blog posts...
</h1>
```

Inside the controller, we said that we want to return the same array that we receive inside the ```/resources/views/blog/index.blade.php```, so we don’t need to change up anything!

## Import existing posts
By default, the existing records in the database will not be imported into Algolia, only records that we created after we started using Algolia. There is a command that makes sure that we import old posts into Algolia.
```
php artisan scout:import “App\Models\Post”
```

With one command, all existing posts are imported into Algolia and they are all searchable!
    
# Credits due where credits due…
Thanks to [Laravel](https://laravel.com/) for giving me the opportunity to make this tutorial on [Laravel Scout](https://laravel.com/docs/8.x/scout).
