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
use Okaufmann\LaravelTmdb\Factory\People\CastFactory;
use Okaufmann\LaravelTmdb\Factory\People\CrewFactory;
use Okaufmann\LaravelTmdb\Client\HttpClient;
use Okaufmann\LaravelTmdb\Model\Common\Country;
use Okaufmann\LaravelTmdb\Model\Common\GenericCollection;
use Okaufmann\LaravelTmdb\Model\Common\SpokenLanguage;
use Okaufmann\LaravelTmdb\Model\Common\Translation;
use Okaufmann\LaravelTmdb\Model\Company;
use Okaufmann\LaravelTmdb\Model\Person\CastMember;
use Okaufmann\LaravelTmdb\Model\Person\CrewMember;
use Okaufmann\LaravelTmdb\Model\Common\ExternalIds;
use Okaufmann\LaravelTmdb\Model\Tv;

/**
 * Class TvFactory
 * @package Tmdb\Factory
 */
class TvFactory extends AbstractFactory
{
    /**
     * @var People\CastFactory
     */
    private $castFactory;

    /**
     * @var ContentRatingsFactory
     */
    private $contentRatingsFactory;

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
     * @var TvSeasonFactory
     */
    private $tvSeasonFactory;

    /**
     * @var NetworkFactory
     */
    private $networkFactory;

    /**
     * @var Common\VideoFactory
     */
    private $videoFactory;

    /**
     * @var ChangeFactory
     */
    private $changesFactory;

    /**
     * @var KeywordFactory
     */
    private $keywordFactory;

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
        $this->tvSeasonFactory = new TvSeasonFactory($httpClient);
        $this->networkFactory  = new NetworkFactory($httpClient);
        $this->videoFactory    = new VideoFactory($httpClient);
        $this->changesFactory  = new ChangeFactory($httpClient);
        $this->keywordFactory  = new KeywordFactory($httpClient);

        parent::__construct($httpClient);
    }

    /**
     * @param array $data
     *
     * @return Tv
     */
    public function create(array $data = [])
    {
        if (!$data) {
            return null;
        }

        $tvShow = new Tv();

        if (array_key_exists('content_ratings', $data) && array_key_exists('results', $data['content_ratings'])) {
            $tvShow->setContentRatings(
                $this->createGenericCollection($data['content_ratings']['results'], new Tv\ContentRating())
            );
        }

        if (array_key_exists('credits', $data)) {
            if (array_key_exists('cast', $data['credits']) && $data['credits']['cast'] !== null) {
                $tvShow->getCredits()->setCast(
                    $this->getCastFactory()->createCollection(
                        $data['credits']['cast'],
                        new CastMember()
                    )
                );
            }

            if (array_key_exists('crew', $data['credits']) && $data['credits']['crew'] !== null) {
                $tvShow->getCredits()->setCrew(
                    $this->getCrewFactory()->createCollection(
                        $data['credits']['crew'],
                        new CrewMember()
                    )
                );
            }
        }

        /** External ids */
        if (array_key_exists('external_ids', $data) && $data['external_ids'] !== null) {
            $tvShow->setExternalIds(
                $this->hydrate(new ExternalIds(), $data['external_ids'])
            );
        }

        /** Genres */
        if (array_key_exists('genres', $data) && $data['genres'] !== null) {
            $tvShow->setGenres($this->getGenreFactory()->createCollection($data['genres']));
        }

        /** Genres */
        if (array_key_exists('genre_ids', $data)) {
            $formattedData = [];

            foreach ($data['genre_ids'] as $genreId) {
                $formattedData[] = [
                    'id' => $genreId
                ];
            }

            $tvShow->setGenres($this->getGenreFactory()->createCollection($formattedData));
        }

        /** Images */
        if (array_key_exists('images', $data) && $data['images'] !== null) {
            $tvShow->setImages(
                $this->getImageFactory()->createCollectionFromTv($data['images'])
            );
        }

        if (array_key_exists('backdrop_path', $data)) {
            $tvShow->setBackdropImage(
                $this->getImageFactory()->createFromPath($data['backdrop_path'], 'backdrop_path')
            );
        }

        if (array_key_exists('poster_path', $data)) {
            $tvShow->setPosterImage(
                $this->getImageFactory()->createFromPath($data['poster_path'], 'poster_path')
            );
        }

        /** Translations */
        if (array_key_exists('translations', $data) && null !== $data['translations']) {

            if (array_key_exists('translations', $data['translations'])) {
                $translations = $data['translations']['translations'];
            } else {
                $translations = $data['translations'];
            }

            $tvShow->setTranslations(
                $this->createGenericCollection($translations, new Translation())
            );
        }

        /** Seasons */
        if (array_key_exists('seasons', $data) && $data['seasons'] !== null) {
            $tvShow->setSeasons($this->getTvSeasonFactory()->createCollection($data['seasons']));
        }

        /** Networks */
        if (array_key_exists('networks', $data) && $data['networks'] !== null) {
            $tvShow->setNetworks($this->getNetworkFactory()->createCollection($data['networks']));
        }

        if (array_key_exists('videos', $data) && $data['videos'] !== null) {
            $tvShow->setVideos($this->getVideoFactory()->createCollection($data['videos']));
        }

        if (array_key_exists('keywords', $data) && array_key_exists('results', $data['keywords'])) {
            $tvShow->setKeywords($this->getKeywordFactory()->createCollection($data['keywords']['results']));
        }

        if (array_key_exists('changes', $data)  && $data['changes'] !== null) {
            $tvShow->setChanges($this->getChangesFactory()->createCollection($data['changes']));
        }

        if (array_key_exists('similar', $data)  && $data['similar'] !== null) {
            $tvShow->setSimilar($this->createResultCollection($data['similar']));
        }

        if (array_key_exists('languages', $data) && $data['languages'] !== null) {
            $collection = new GenericCollection();

            foreach ($data['languages'] as $iso6391) {
                $object = new SpokenLanguage();
                $object->setIso6391($iso6391);

                $collection->add(null, $object);
            }

            $tvShow->setLanguages($collection);
        }

        if (array_key_exists('origin_country', $data) && $data['origin_country'] !== null) {
            $collection = new GenericCollection();

            foreach ($data['origin_country'] as $iso31661) {
                $object = new Country();
                $object->setIso31661($iso31661);

                $collection->add(null, $object);
            }

            $tvShow->setOriginCountry($collection);
        }

        if (array_key_exists('production_companies', $data)) {
            $tvShow->setProductionCompanies(
                $this->createGenericCollection($data['production_companies'], new Company())
            );
        }

        if (array_key_exists('created_by', $data) && $data['created_by'] !== null) {
            $collection = new GenericCollection();
            $factory =  new PeopleFactory($this->getHttpClient());

            foreach ($data['created_by'] as $castMember) {
                $object = $factory->create($castMember, new CastMember());

                $collection->add(null, $object);
            }

            $tvShow->setCreatedBy($collection);
        }

        if (array_key_exists('alternative_titles', $data) && array_key_exists('results', $data['alternative_titles'])) {
            $tvShow->setAlternativeTitles(
                $this->createGenericCollection($data['alternative_titles']['results'], new Tv\AlternativeTitle())
            );
        }

        return $this->hydrate($tvShow, $data);
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
     * @return \Okaufmann\LaravelTmdb\Factory\ContentRatingsFactory
     */
    public function getContentRatingsFactory()
    {
        return $this->contentRatingsFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\ContentRatingFactory $contentRatingFactory
     * @return $this
     */
    public function setContentRatingsFactory($contentRatingsFactory)
    {
        $this->contentRatingsFactory = $contentRatingsFactory;
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
     * @param  \Okaufmann\LaravelTmdb\Factory\TvSeasonFactory $tvSeasonFactory
     * @return $this
     */
    public function setTvSeasonFactory($tvSeasonFactory)
    {
        $this->tvSeasonFactory = $tvSeasonFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\TvSeasonFactory
     */
    public function getTvSeasonFactory()
    {
        return $this->tvSeasonFactory;
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\NetworkFactory $networkFactory
     * @return $this
     */
    public function setNetworkFactory($networkFactory)
    {
        $this->networkFactory = $networkFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\NetworkFactory
     */
    public function getNetworkFactory()
    {
        return $this->networkFactory;
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

    /**
     * @param  \Okaufmann\LaravelTmdb\Factory\Common\ChangeFactory $changesFactory
     * @return $this
     */
    public function setChangesFactory($changesFactory)
    {
        $this->changesFactory = $changesFactory;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Factory\Common\ChangeFactory
     */
    public function getChangesFactory()
    {
        return $this->changesFactory;
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
}
