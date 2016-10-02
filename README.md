LaravelTmdb
=================

LaravelTmdb was created by, and is maintained by [Oliver Kaufmann](https://github.com/). Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/ptondereau/laravel-packme/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).

## Forked
This Repo is a fork of the official [The Movie DB Client](https://github.com/php-tmdb/api/).
It was modified and simplified to match my own needs.

**Please point donations and fame to the base repo without this packages wouldn't be possible!**

**Differences**
- Removed Event based Request handling
- Removed Guzzle 5 and integrated Guzzle 6
- Removed Logging
- Integrate Laravel-Support and Client into one package
- Plugins not working atm. 

## Features

**Main features**

- An complete integration of all the TMDB API has to offer ( accounts, movies, tv etc. _if something is missing I haven't added the updates yet!_ ).
- Array implementation of the movie database ( RAW )
- Model implementation of the movie database ( By making use of the repositories )
- An `ImageHelper` class to help build image urls or html <img> elements.

**Other things worth mentioning**

- Retry subscriber enabled by default to handle any rate limit errors.
- Caching subscriber enabled by default, based on `max-age` headers returned by TMDB, requires `doctrine-cache`.
- Logging subscriber and is optional, requires `monolog`. Could prove useful during development.

## Installation

[PHP](https://php.net) 7.0+ is required.

To get the latest version of LaravelTmdb, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require okaufmann/laravel-tmdb
```

## Configuration

LaravelTmdb provides a configuration example.

So you can test publishing assets with:

```bash
$ php artisan vendor:publish --provider="Okaufmann\LaravelTmdb\LaravelTmdbServiceProvider"
```

This will create a `config/laraveltmdb` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

**Notice**: You have to use an key for the API of [The Movie DB](https://www.themoviedb.org/). You can create one here: https://www.themoviedb.org/documentation/api.

## Usage

To use the Client just need to add it to your Method parameters and it will be injected.
```php
class MovieController extends Controller
{
    private $client;

    public function __construct(LaravelTmdb $client){
        $this->client = $client;
    }
}

```

General API Usage (Get json response)
-----------------

If your looking for a simple array entry point the API namespace is the place to be.

```php
$movie = $client->getMoviesApi()->getMovie(550);
```

If you want to provide any other query arguments.

```php
$movie = $client->getMoviesApi()->getMovie(550, ['language' => 'en']);
```

Model Usage (working with full typed response)
-----------

However the library can also be used in an object oriented manner, which I reckon is the __preferred__ way of doing things.

Instead of calling upon the client, you pass the client onto one of the many repositories and do then some work on it.

```php
$repository = new \Tmdb\Repository\MovieRepository($client);
$movie      = $repository->load(87421);

echo $movie->getTitle();
```

__The repositories also contain the other API methods that are available through the API namespace.__

```php
$repository = new \Tmdb\Repository\MovieRepository($client);
$topRated = $repository->getTopRated(array('page' => 3));
// or
$popular = $repository->getPopular();
```


##### Further Information

Review the official API Documentation here: https://developers.themoviedb.org/3/getting-started or the base repos readme here: https://github.com/php-tmdb/api/blob/2.0/README.md.

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.

## License

LaravelTmdb is licensed under [The MIT License (MIT)](LICENSE).