<?php

namespace Okaufmann\LaravelTmdb\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the Dummy facade class.
 *
 * @author {{ author }}
 */
class DummyClass extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laraveltmdb.dummyclass';
    }
}