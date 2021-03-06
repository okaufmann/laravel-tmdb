<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Okaufmann\LaravelTmdb\Repository;

use Okaufmann\LaravelTmdb\Factory\TimezoneFactory;
use Okaufmann\LaravelTmdb\Model\Collection\Timezones;

/**
 * Class TimezoneRepository
 * @package Tmdb\Repository
 * @see http://docs.themoviedb.apiary.io/#timezones
 */
class TimezoneRepository extends AbstractRepository
{
    /**
     * Get the list of supported timezones for the API methods that support them.
     *
     * @return Timezones
     */
    public function getTimezones()
    {
        $data = $this->getApi()->getTimezones();

        return $this->getFactory()->createCollection($data);
    }

    /**
     * Return the Collection API Class
     *
     * @return \Okaufmann\LaravelTmdb\Api\Timezones
     */
    public function getApi()
    {
        return $this->getClient()->getTimezonesApi();
    }

    /**
     * @return TimezoneFactory
     */
    public function getFactory()
    {
        return new TimezoneFactory($this->getClient()->getHttpClient());
    }
}
