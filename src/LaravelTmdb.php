<?php
/*
 * This file is part of YourPackage.
 *
 * (c) {{ author }}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Okaufmann\LaravelTmdb;

use Illuminate\Contracts\Config\Repository;

/**
 * This is the Dummy class.
 *
 * @author {{ author }}
 */
class LaravelTmdb
{
    /**
     * Foo.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Config repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Create a new dummy instance.
     *
     * @param Repository $config
     *
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->apt_key = array_get($config, 'key');
    }

    /**
     * Return foo.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
