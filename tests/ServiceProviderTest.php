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

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Okaufmann\LaravelTmdb\DummyClass;

/**
 * This is the service provider test class.
 *
 * @author {{ author }}
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testDummyClassIsInjectable()
    {
        $this->assertIsInjectable(DummyClass::class);
    }
}
