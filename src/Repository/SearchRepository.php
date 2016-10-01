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

use Okaufmann\LaravelTmdb\LaravelTmdb as Client;
use Okaufmann\LaravelTmdb\Exception\NotImplementedException;
use Okaufmann\LaravelTmdb\Factory\CollectionFactory;
use Okaufmann\LaravelTmdb\Factory\CompanyFactory;
use Okaufmann\LaravelTmdb\Factory\KeywordFactory;
use Okaufmann\LaravelTmdb\Factory\Movie\ListItemFactory;
use Okaufmann\LaravelTmdb\Factory\MovieFactory;
use Okaufmann\LaravelTmdb\Factory\PeopleFactory;
use Okaufmann\LaravelTmdb\Factory\TvFactory;
use Okaufmann\LaravelTmdb\Model\Collection\ResultCollection;
use Okaufmann\LaravelTmdb\Model\Company;
use Okaufmann\LaravelTmdb\Model\Keyword;
use Okaufmann\LaravelTmdb\Model\Movie;
use Okaufmann\LaravelTmdb\Model\Person;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\CollectionSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\CompanySearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\KeywordSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\ListSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\MovieSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\PersonSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery\TvSearchQuery;
use Okaufmann\LaravelTmdb\Model\Search\SearchQuery;
use Okaufmann\LaravelTmdb\Model\Tv;

/**
 * Class SearchRepository
 * @package Tmdb\Repository
 * @see http://docs.themoviedb.apiary.io/#search
 */
class SearchRepository extends AbstractRepository
{
    /**
     * @var MovieFactory
     */
    private $movieFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var TvFactory
     */
    private $tvFactory;

    /**
     * @var PeopleFactory
     */
    private $peopleFactory;

    /**
     * @var ListItemFactory
     */
    private $listItemFactory;

    /**
     * @var CompanyFactory
     */
    private $companyFactory;

    /**
     * @var KeywordFactory
     */
    private $keywordFactory;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->movieFactory      = new MovieFactory($this->getClient()->getHttpClient());
        $this->collectionFactory = new CollectionFactory($this->getClient()->getHttpClient());
        $this->tvFactory         = new TvFactory($this->getClient()->getHttpClient());
        $this->peopleFactory     = new PeopleFactory($this->getClient()->getHttpClient());
        $this->listItemFactory   = new ListItemFactory($this->getClient()->getHttpClient());
        $this->companyFactory    = new CompanyFactory($this->getClient()->getHttpClient());
        $this->keywordFactory    = new KeywordFactory($this->getClient()->getHttpClient());
    }

    /**
     * @param string           $query
     * @param MovieSearchQuery $parameters
     * @param array            $headers
     *
     * @return ResultCollection|Movie[]
     */
    public function searchMovie($query, MovieSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchMovies($query, $this->getParameters($parameters), $headers);

        return $this->getMovieFactory()->createResultCollection($data);
    }

    /**
     * @param string                $query
     * @param CollectionSearchQuery $parameters
     * @param array                 $headers
     *
     * @return ResultCollection[]
     */
    public function searchCollection($query, CollectionSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchCollection($query, $this->getParameters($parameters), $headers);

        return $this->getCollectionFactory()->createResultCollection($data);
    }

    /**
     * @param string        $query
     * @param TvSearchQuery $parameters
     * @param array         $headers
     *
     * @return ResultCollection|Tv[]
     */
    public function searchTv($query, TvSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchTv($query, $this->getParameters($parameters), $headers);

        return $this->getTvFactory()->createResultCollection($data);
    }

    /**
     * @param string            $query
     * @param PersonSearchQuery $parameters
     * @param array             $headers
     *
     * @return ResultCollection|Person[]
     */
    public function searchPerson($query, PersonSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchPersons($query, $this->getParameters($parameters), $headers);

        return $this->getPeopleFactory()->createResultCollection($data);
    }

    /**
     * @param string          $query
     * @param ListSearchQuery $parameters
     * @param array           $headers
     *
     * @return ResultCollection
     */
    public function searchList($query, ListSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchList($query, $this->getParameters($parameters), $headers);

        return $this->getListitemFactory()->createResultCollection($data);
    }

    /**
     * @param string             $query
     * @param CompanySearchQuery $parameters
     * @param array              $headers
     *
     * @return ResultCollection|Company[]
     */
    public function searchCompany($query, CompanySearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchCompany($query, $this->getParameters($parameters), $headers);

        return $this->getCompanyFactory()->createResultCollection($data);
    }

    /**
     * @param string             $query
     * @param KeywordSearchQuery $parameters
     * @param array              $headers
     *
     * @return ResultCollection|Keyword[]
     */
    public function searchKeyword($query, KeywordSearchQuery $parameters, array $headers = [])
    {
        $data = $this->getApi()->searchKeyword($query, $this->getParameters($parameters), $headers);

        return $this->getKeywordFactory()->createResultCollection($data);
    }

    /**
     * @param string             $query
     * @param KeywordSearchQuery $parameters
     * @param array              $headers
     *
     * @return ResultCollection|Keyword[]
     */
    public function searchMulti($query, KeywordSearchQuery $parameters, array $headers = [])
    {
        $data       = $this->getApi()->searchMulti($query, $this->getParameters($parameters), $headers);
        $collection = new ResultCollection();

        if (null === $data) {
            return $collection;
        }

        if (array_key_exists('page', $data)) {
            $collection->setPage($data['page']);
        }

        if (array_key_exists('total_pages', $data)) {
            $collection->setTotalPages($data['total_pages']);
        }

        if (array_key_exists('total_results', $data)) {
            $collection->setTotalResults($data['total_results']);
        }

        if (array_key_exists('results', $data)) {
            foreach ($data['results'] as $item) {
                if ($item) {
                    $collection->add(null, $this->processSearchMultiItem($item));
                }
            }
        }

        return $collection;
    }

    /**
     * Process multi search items
     *
     * @param  array                                                    $item
     * @return bool|Movie|Person|Person\CastMember|Person\CrewMember|Tv
     * @throws \RuntimeException
     */
    private function processSearchMultiItem(array $item)
    {
        if (array_key_exists('media_type', $item)) {
            switch ($item['media_type']) {
                case 'movie':
                    return $this->getMovieFactory()->create($item);
                case 'tv':
                    return $this->getTvFactory()->create($item);
                case 'person':
                    return $this->getPeopleFactory()->create($item);
                default:
                    throw new \RuntimeException(sprintf(
                        'Could not process media_type "%s" in multi search, type unknown.',
                        $item['media_type']
                    ));
            }
        }

        return false;
    }

    /**
     * Convert parameters back to an array
     *
     * @param  SearchQuery|array $parameters
     * @return array
     */
    private function getParameters($parameters = [])
    {
        if ($parameters instanceof SearchQuery) {
            return $parameters->toArray();
        }

        return $parameters;
    }

    /**
     * Return the related API class
     *
     * @return \Okaufmann\LaravelTmdb\Api\Search
     */
    public function getApi()
    {
        return $this->getClient()->getSearchApi();
    }

    /**
     * SearchRepository does not support a generic factory
     *
     * @throws NotImplementedException
     */
    public function getFactory()
    {
        throw new NotImplementedException('SearchRepository does not support a generic factory.');
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
     * @param  \Okaufmann\LaravelTmdb\Factory\CollectionFactory $collectionFactory
     * @return $this
     */
    public function setCollectionFactory($collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\CollectionFactory
     */
    public function getCollectionFactory()
    {
        return $this->collectionFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\CompanyFactory $companyFactory
     * @return $this
     */
    public function setCompanyFactory($companyFactory)
    {
        $this->companyFactory = $companyFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\CompanyFactory
     */
    public function getCompanyFactory()
    {
        return $this->companyFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\KeywordFactory $keywordFactory
     * @return $this
     */
    public function setKeywordFactory($keywordFactory)
    {
        $this->keywordFactory = $keywordFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\KeywordFactory
     */
    public function getKeywordFactory()
    {
        return $this->keywordFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\Movie\ListItemFactory $listItemFactory
     * @return $this
     */
    public function setListItemFactory($listItemFactory)
    {
        $this->listItemFactory = $listItemFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\Movie\ListItemFactory
     */
    public function getListItemFactory()
    {
        return $this->listItemFactory;
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
}
