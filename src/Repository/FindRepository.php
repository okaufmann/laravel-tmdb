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

use Okaufmann\LaravelTmdb\Factory\FindFactory;
use Okaufmann\LaravelTmdb\Model\Find;

/**
 * Class FindRepository
 * @package Tmdb\Repository
 * @see http://docs.themoviedb.apiary.io/#find
 */
class FindRepository extends AbstractRepository
{
    /**
     * Find something
     *
     * @param $id
     * @param  array $parameters
     * @param  array $headers
     * @return Find
     */
    public function findBy($id, array $parameters = [], array $headers = [])
    {
        return $this->getFactory()->create(
            $this->getApi()->findBy($id, $parameters, $headers)
        );
    }

    /**
     * Return the related API class
     *
     * @return \Okaufmann\LaravelTmdb\Api\Find
     */
    public function getApi()
    {
        return $this->getClient()->getFindApi();
    }

    /**
     * @return FindFactory
     */
    public function getFactory()
    {
        return new FindFactory($this->getClient()->getHttpClient());
    }
}
