<?php
/*
 * This file is part of LaravelTmdb.
 *
 * (c) {{ author }}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Okaufmann\LaravelTmdb;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the YourPackage service provider class.
 *
 * @author {{ author }}
 */
class LaravelTmdbServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/laraveltmdb.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('laraveltmdb.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laraveltmdb');
        }
        $this->mergeConfigFrom($source, 'laraveltmdb');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDummyClass();
    }

    /**
     * Register the auth factory class.
     *
     * @return void
     */
    protected function registerDummyClass()
    {
        $this->app->singleton('laraveltmdb.dummyclass', function (Container $app) {
            return new DummyClass($app['config']);
        });
        $this->app->alias('laraveltmdb.dummyclass', DummyClass::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['laraveltmdb.dummyclass'];
    }
}
