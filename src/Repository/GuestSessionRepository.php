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

use Okaufmann\LaravelTmdb\Factory\GuestSessionFactory;
use Okaufmann\LaravelTmdb\Factory\MovieFactory;
use Okaufmann\LaravelTmdb\Model\Collection\ResultCollection;
use Okaufmann\LaravelTmdb\Model\Movie;

/**
 * Class GuestSessionRepository
 * @package Tmdb\Repository
 * @see http://docs.themoviedb.apiary.io/#guestsessions
 */
class GuestSessionRepository extends AbstractRepository
{
    /**
     * Get the list of top rated movies.
     *
     * By default, this list will only include movies that have 10 or more votes.
     * This list refreshes every day.
     *
     * @param  array                    $options
     * @return ResultCollection|Movie[]
     */
    public function getRatedMovies(array $options = [])
    {
        return $this->getMovieFactory()->createResultCollection(
            $this->getApi()->getRatedMovies($options)
        );
    }

    /**
     * Return the Movies API Class
     *
     * @return \Okaufmann\LaravelTmdb\Api\GuestSession
     */
    public function getApi()
    {
        return $this->getClient()->getGuestSessionApi();
    }

    /**
     * Return the Guest Session Factory
     *
     * @return GuestSessionFactory
     */
    public function getFactory()
    {
        return new GuestSessionFactory($this->getClient()->getHttpClient());
    }

    /**
     * @return MovieFactory
     */
    public function getMovieFactory()
    {
        return new MovieFactory($this->getClient()->getHttpClient());
    }
}
