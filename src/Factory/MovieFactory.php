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

use Okaufmann\LaravelTmdb\Factory\Common\ChangeFactory;
use Okaufmann\LaravelTmdb\Factory\Common\VideoFactory;
use Okaufmann\LaravelTmdb\Factory\Movie\ListItemFactory;
use Okaufmann\LaravelTmdb\Factory\People\CastFactory;
use Okaufmann\LaravelTmdb\Factory\People\CrewFactory;
use Okaufmann\LaravelTmdb\Client\HttpClient;
use Okaufmann\LaravelTmdb\Model\Common\Country;
use Okaufmann\LaravelTmdb\Model\Common\GenericCollection;
use Okaufmann\LaravelTmdb\Model\Common\SpokenLanguage;
use Okaufmann\LaravelTmdb\Model\Common\Translation;
use Okaufmann\LaravelTmdb\Model\Company;
use Okaufmann\LaravelTmdb\Model\Movie;

/**
 * Class MovieFactory
 * @package Tmdb\Factory
 */
class MovieFactory extends AbstractFactory
{
    /**
     * @var People\CastFactory
     */
    private $castFactory;

    /**
     * @var People\CrewFactory
     */
    private $crewFactory;

    /**
     * @var GenreFactory
     */
    private $genreFactory;

    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @var ChangeFactory
     */
    private $changeFactory;

    /**
     * @var ReviewFactory
     */
    private $reviewFactory;

    /**
     * @var ListItemFactory
     */
    private $listItemFactory;

    /**
     * @var KeywordFactory
     */
    private $keywordFactory;

    /**
     * @var Common\VideoFactory
     */
    private $videoFactory;

    /**
     * Constructor
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->castFactory     = new CastFactory($httpClient);
        $this->crewFactory     = new CrewFactory($httpClient);
        $this->genreFactory    = new GenreFactory($httpClient);
        $this->imageFactory    = new ImageFactory($httpClient);
        $this->changeFactory   = new ChangeFactory($httpClient);
        $this->reviewFactory   = new ReviewFactory($httpClient);
        $this->listItemFactory = new ListItemFactory($httpClient);
        $this->keywordFactory  = new KeywordFactory($httpClient);
        $this->videoFactory    = new VideoFactory($httpClient);

        parent::__construct($httpClient);
    }

    /**
     * @param  array $data
     * @return Movie
     */
    public function create(array $data = [])
    {
        if (!$data) {
            return null;
        }

        $movie = new Movie();

        if (array_key_exists('alternative_titles', $data) && array_key_exists('titles', $data['alternative_titles'])) {
            $movie->setAlternativeTitles(
                $this->createGenericCollection($data['alternative_titles']['titles'], new Movie\AlternativeTitle())
            );
        }

        if (array_key_exists('credits', $data)) {
            if (array_key_exists('cast', $data['credits'])) {
                $movie->getCredits()->setCast($this->getCastFactory()->createCollection($data['credits']['cast']));
            }

            if (array_key_exists('crew', $data['credits'])) {
                $movie->getCredits()->setCrew($this->getCrewFactory()->createCollection($data['credits']['crew']));
            }
        }

        /** Genres */
        if (array_key_exists('genres', $data)) {
            $movie->setGenres($this->getGenreFactory()->createCollection($data['genres']));
        }

        /** Genres */
        if (array_key_exists('genre_ids', $data)) {
            $formattedData = [];

            foreach ($data['genre_ids'] as $genreId) {
                $formattedData[] = [
                    'id' => $genreId
                ];
            }

            $movie->setGenres($this->getGenreFactory()->createCollection($formattedData));
        }

        /** Images */
        if (array_key_exists('backdrop_path', $data)) {
            $movie->setBackdropImage($this->getImageFactory()->createFromPath($data['backdrop_path'], 'backdrop_path'));
        }

        if (array_key_exists('images', $data)) {
            $movie->setImages($this->getImageFactory()->createCollectionFromMovie($data['images']));
        }

        if (array_key_exists('poster_path', $data)) {
            $movie->setPosterImage($this->getImageFactory()->createFromPath($data['poster_path'], 'poster_path'));
        }

        /** Keywords */
        if (array_key_exists('keywords', $data)) {
            $movie->setKeywords($this->getKeywordFactory()->createCollection($data['keywords']));
        }

        if (array_key_exists('releases', $data) && array_key_exists('countries', $data['releases'])) {
            $movie->setReleases($this->createGenericCollection($data['releases']['countries'], new Movie\Release()));
        }

        if (array_key_exists('videos', $data)) {
            $movie->setVideos($this->getVideoFactory()->createCollection($data['videos']));
        }

        if (array_key_exists('translations', $data) && array_key_exists('translations', $data['translations'])) {
            $movie->setTranslations(
                $this->createGenericCollection(
                    $data['translations']['translations'],
                    new Translation()
                )
            );
        }

        if (array_key_exists('similar', $data)) {
            $movie->setSimilar($this->createResultCollection($data['similar']));
        }

        if (array_key_exists('reviews', $data)) {
            $movie->setReviews($this->getReviewFactory()->createResultCollection($data['reviews']));
        }

        if (array_key_exists('lists', $data)) {
            $movie->setLists($this->getListItemFactory()->createResultCollection($data['lists']));
        }

        if (array_key_exists('changes', $data)) {
            $movie->setChanges($this->getChangeFactory()->createCollection($data['changes']));
        }

        if (array_key_exists('production_companies', $data)) {
            $movie->setProductionCompanies(
                $this->createGenericCollection($data['production_companies'], new Company())
            );
        }

        if (array_key_exists('production_countries', $data)) {
            $movie->setProductionCountries(
                $this->createGenericCollection($data['production_countries'], new Country())
            );
        }

        if (array_key_exists('spoken_languages', $data)) {
            $movie->setSpokenLanguages(
                $this->createGenericCollection($data['spoken_languages'], new SpokenLanguage())
            );
        }

        return $this->hydrate($movie, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection(array $data = [])
    {
        $collection = new GenericCollection();

        if (array_key_exists('results', $data)) {
            $data = $data['results'];
        }

        foreach ($data as $item) {
            $collection->add(null, $this->create($item));
        }

        return $collection;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\People\CastFactory $castFactory
     * @return $this
     */
    public function setCastFactory($castFactory)
    {
        $this->castFactory = $castFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\People\CastFactory
     */
    public function getCastFactory()
    {
        return $this->castFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\People\CrewFactory $crewFactory
     * @return $this
     */
    public function setCrewFactory($crewFactory)
    {
        $this->crewFactory = $crewFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\People\CrewFactory
     */
    public function getCrewFactory()
    {
        return $this->crewFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\GenreFactory $genreFactory
     * @return $this
     */
    public function setGenreFactory($genreFactory)
    {
        $this->genreFactory = $genreFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\GenreFactory
     */
    public function getGenreFactory()
    {
        return $this->genreFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\ImageFactory $imageFactory
     * @return $this
     */
    public function setImageFactory($imageFactory)
    {
        $this->imageFactory = $imageFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\ImageFactory
     */
    public function getImageFactory()
    {
        return $this->imageFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\Common\ChangeFactory $changeFactory
     * @return $this
     */
    public function setChangeFactory($changeFactory)
    {
        $this->changeFactory = $changeFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\Common\ChangeFactory
     */
    public function getChangeFactory()
    {
        return $this->changeFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\ReviewFactory $reviewFactory
     * @return $this
     */
    public function setReviewFactory($reviewFactory)
    {
        $this->reviewFactory = $reviewFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\ReviewFactory
     */
    public function getReviewFactory()
    {
        return $this->reviewFactory;
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
     * @param  \Okaufmann\LaravelTmdb\Factory\Common\VideoFactory $videoFactory
     * @return $this
     */
    public function setVideoFactory($videoFactory)
    {
        $this->videoFactory = $videoFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\Common\VideoFactory
     */
    public function getVideoFactory()
    {
        return $this->videoFactory;
    }
}
