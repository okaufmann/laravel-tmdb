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
namespace Okaufmann\LaravelTmdb\Factory;

use Okaufmann\LaravelTmdb\Client\HttpClient;
use Okaufmann\LaravelTmdb\Model\Find;

/**
 * Class FindFactory
 * @package Tmdb\Factory
 */
class FindFactory extends AbstractFactory
{
    /**
     * @var MovieFactory
     */
    private $movieFactory;

    /**
     * @var PeopleFactory
     */
    private $peopleFactory;

    /**
     * @var TvFactory
     */
    private $tvFactory;

    /**
     * @var TvSeasonFactory
     */
    private $tvSeasonFactory;

    /**
     * @var TvEpisodeFactory
     */
    private $tvEpisodeFactory;

    /**
     * Constructor
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->movieFactory     = new MovieFactory($httpClient);
        $this->peopleFactory    = new PeopleFactory($httpClient);
        $this->tvFactory        = new TvFactory($httpClient);
        $this->tvSeasonFactory  = new TvSeasonFactory($httpClient);
        $this->tvEpisodeFactory = new TvEpisodeFactory($httpClient);

        parent::__construct($httpClient);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data = [])
    {
        $find = new Find();

        if (array_key_exists('movie_results', $data)) {
            $find->setMovieResults($this->getMovieFactory()->createCollection($data['movie_results']));
        }

        if (array_key_exists('person_results', $data)) {
            $find->setPersonResults($this->getPeopleFactory()->createCollection($data['person_results']));
        }

        if (array_key_exists('tv_results', $data)) {
            $find->setTvResults($this->getTvFactory()->createCollection($data['tv_results']));
        }

        if (array_key_exists('tv_season_results', $data)) {
            $find->setTvSeasonResults($this->getTvSeasonFactory()->createCollection($data['tv_season_results']));
        }

        if (array_key_exists('tv_episode_results', $data)) {
            $find->setTvEpisodeResults($this->getTvSeasonFactory()->createCollection($data['tv_episode_results']));
        }

        return $find;
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection(array $data = [])
    {
        throw new \RuntimeException(sprintf(
            'Class "%s" does not support method "%s".',
            __CLASS__,
            __METHOD__
        ));
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\MovieFactory $movieFactory
     * @return $this
     */
    public function setMovieFactory($movieFactory)
    {
        $this->movieFactory = $movieFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\MovieFactory
     */
    public function getMovieFactory()
    {
        return $this->movieFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\PeopleFactory $peopleFactory
     * @return $this
     */
    public function setPeopleFactory($peopleFactory)
    {
        $this->peopleFactory = $peopleFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\PeopleFactory
     */
    public function getPeopleFactory()
    {
        return $this->peopleFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\TvFactory $tvFactory
     * @return $this
     */
    public function setTvFactory($tvFactory)
    {
        $this->tvFactory = $tvFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\TvFactory
     */
    public function getTvFactory()
    {
        return $this->tvFactory;
    }

    /**
     * @return TvEpisodeFactory
     */
    public function getTvEpisodeFactory()
    {
        return $this->tvEpisodeFactory;
    }

    /**
     * @param  TvEpisodeFactory $tvEpisodeFactory
     * @return $this
     */
    public function setTvEpisodeFactory($tvEpisodeFactory)
    {
        $this->tvEpisodeFactory = $tvEpisodeFactory;

        return $this;
    }

    /**
     * @return TvSeasonFactory
     */
    public function getTvSeasonFactory()
    {
        return $this->tvSeasonFactory;
    }

    /**
     * @param  TvSeasonFactory $tvSeasonFactory
     * @return $this
     */
    public function setTvSeasonFactory($tvSeasonFactory)
    {
        $this->tvSeasonFactory = $tvSeasonFactory;

        return $this;
    }
}
