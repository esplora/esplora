# <img src=".github/logo.svg?sanitize=true" width="24" height="24" alt="Esplora"> Esplora - Easy Tracker Visits


Esplora is an open-source package for Laravel with which you can easily collect visitor analytics. All analytic data about website visitors that the package contains and structures belong solely to the website owner. Don't sell your users! Use your own private storage.

## How It Works

After the client has received a useful response from the server, we process his request by collecting and write to the database the following information:

- Requested URL
- IP address 
- Device (Mobile, Desktop, Tablet, Robot)
- Platform (Ubuntu, Windows, OS X)
- Browser (Chrome, IE, Safari, Firefox)
- Preferred Language
- Referer

## Installation

You may install Esplora into your project using the Composer package manager:

```bash
composer require esplora/esplora
```

Next, you should publish the Esplora configuration and migration files using the `vendor:publish` Artisan command. The `esplora.php` configuration file will be placed in your application's `config` directory:

```bash
php artisan vendor:publish --provider="Esplora\Tracker\EsploraServiceProvider.php"
```

Finally, you should run your database migrations. Esplora will create  database tables in which to store users visits:

```bash
php artisan migrate
```

Next, if you plan to utilize Esplora to tracking web requests, you should add middleware to your `web` middleware group within your application's `app/Http/Kernel.php` file:

```php
'web' => [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    // \Illuminate\Session\Middleware\AuthenticateSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    
    //new Esplora:
    \Esplora\Tracker\Middleware\Tracking::class,
],
```

Or set for a specific route:

```php
use Esplora\Tracker\Middleware\Tracking;

Route::get('about', function (){
  // code
})->middleware(Tracking::class);
```

## Configuration

After publishing Esplora's assets, its primary configuration file will be located at `config/esplora.php`. This
configuration file allows you to configure the request tracking options for your application. Each configuration option
includes a description of its purpose, so be sure to explore this file thoroughly.

## Executing Goals

Goals allow you to track important events on the site: clicks on buttons, views of certain pages, downloading files,
submitting forms, and many others. You can define a target as completed with a simple call:

```php
use Esplora\Tracker\Facades\Tracker;

Tracker::goal('Dark theme', [
    'enabled' => false,
]);
```

## Batch Import

With many visits, the number of `Insert` queries to the database can take up a lot of server resources. To do this, you
can use an intermediate Redis store, from which information will then be inserted in batches.

```bash
php artisan esplora:insert
```

You may define the time of recording to permanent storage using a [schedule](https://laravel.com/docs/8.x/scheduling) of scheduled tasks in the `schedule` method
of your application's `App\Console\Kernel` class. For example, every 10 minutes:

```php
$schedule->command('esplora:insert')->everyTenMinutes();
```


## Data Pruning

Without pruning, the `esplora_visits` and `esplora_goals` tables can accumulate records very quickly. To mitigate this, you should schedule the `model:prune` Artisan command to run daily:

```php
use Esplora\Tracker\Models\Visit;
use Esplora\Tracker\Models\Goal;

$schedule->command('model:prune', [
    '--model' => [Visit::class, Goal::class],
])->daily();
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
