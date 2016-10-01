<?php
/*
 * This file is part of LaravelTmdb.
 *
 * (c) {{ author }}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Okaufmann\Tests\LaravelTmdb;

use Okaufmann\LaravelTmdb\LaravelTmdb;

/**
 * This is the dummy test class.
 *
 * @author {{ author }}
 */
class LaravelTmdbTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $dummy = new LaravelTmdb($this->app['config']);
        $this->assertInstanceOf(LaravelTmdb::class, $dummy);
    }

    public function testGetFoo()
    {
        $dummy = new LaravelTmdb($this->app['config']);
        $this->assertSame('bar', $dummy->getApiKey());
    }
}
