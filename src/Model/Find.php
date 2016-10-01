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
namespace Okaufmann\LaravelTmdb\Model;

use Okaufmann\LaravelTmdb\Model\Collection\People;
use Okaufmann\LaravelTmdb\Model\Common\GenericCollection;

/**
 * Class Find
 * @package Tmdb\Model
 */
class Find extends AbstractModel
{
    /**
     * @var GenericCollection
     */
    private $movieResults;

    /**
     * @var People
     */
    private $personResults;

    /**
     * @var GenericCollection
     */
    private $tvResults;

    /**
     * @var GenericCollection
     */
    private $tvSeasonResults;

    /**
     * @var GenericCollection
     */
    private $tvEpisodeResults;

    /**
     * @param  \Okaufmann\LaravelTmdb\Model\Common\GenericCollection $movieResults
     * @return $this
     */
    public function setMovieResults($movieResults)
    {
        $this->movieResults = $movieResults;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Model\Common\GenericCollection
     */
    public function getMovieResults()
    {
        return $this->movieResults;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Model\Collection\People $personResults
     * @return $this
     */
    public function setPersonResults($personResults)
    {
        $this->personResults = $personResults;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Model\Collection\People
     */
    public function getPersonResults()
    {
        return $this->personResults;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Model\Common\GenericCollection $tvResults
     * @return $this
     */
    public function setTvResults($tvResults)
    {
        $this->tvResults = $tvResults;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Model\Common\GenericCollection
     */
    public function getTvResults()
    {
        return $this->tvResults;
    }

    /**
     * @return GenericCollection
     */
    public function getTvSeasonResults()
    {
        return $this->tvSeasonResults;
    }

    /**
     * @param  GenericCollection $tvSeasonResults
     * @return $this
     */
    public function setTvSeasonResults($tvSeasonResults)
    {
        $this->tvSeasonResults = $tvSeasonResults;

        return $this;
    }

    /**
     * @return GenericCollection
     */
    public function getTvEpisodeResults()
    {
        return $this->tvEpisodeResults;
    }

    /**
     * @param  GenericCollection $tvEpisodeResults
     * @return $this
     */
    public function setTvEpisodeResults($tvEpisodeResults)
    {
        $this->tvEpisodeResults = $tvEpisodeResults;

        return $this;
    }
}
