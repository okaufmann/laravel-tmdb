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

use Okaufmann\LaravelTmdb\Factory\JobsFactory;
use Okaufmann\LaravelTmdb\Model\Collection\Jobs;
use Okaufmann\LaravelTmdb\Model\Job;

/**
 * Class JobsRepository
 * @package Tmdb\Repository
 * @see http://docs.themoviedb.apiary.io/#jobs
 */
class JobsRepository extends AbstractRepository
{
    /**
     * @param  array $parameters
     * @param  array $headers
     * @return Job
     */
    public function load(array $parameters = [], array $headers = [])
    {
        return $this->loadCollection($parameters, $headers);
    }

    /**
     * Get the list of jobs.
     *
     * @param  array      $parameters
     * @param  array      $headers
     * @return Jobs|Job[]
     */
    public function loadCollection(array $parameters = [], array $headers = [])
    {
        return $this->createCollection(
            $this->getApi()->getJobs($parameters, $headers)
        );
    }

    /**
     * Create an collection of an array
     *
     * @param $data
     * @return Jobs|Job[]
     */
    private function createCollection($data)
    {
        return $this->getFactory()->createCollection($data);
    }

    /**
     * Return the related API class
     *
     * @return \Okaufmann\LaravelTmdb\Api\Jobs
     */
    public function getApi()
    {
        return $this->getClient()->getJobsApi();
    }

    /**
     * @return JobsFactory
     */
    public function getFactory()
    {
        return new JobsFactory($this->getClient()->getHttpClient());
    }
}
