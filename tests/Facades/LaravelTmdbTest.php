<?php

namespace Okaufmann\Tests\LaravelTmdb\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use Okaufmann\Tests\LaravelTmdb\AbstractTestCase;

/**
 * This is the UpsLocatorTest facade test class.
 *
 * @author {{ author }}
 */
class LaravelTmdbTest extends AbstractTestCase
{
    use FacadeTrait;
    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'laraveltmdb.dummyclass';
    }
    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return \Okaufmann\LaravelTmdb\Facades\DummyClass::class;
    }
    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return \Okaufmann\LaravelTmdb\LaravelTmdb::class;
    }
}