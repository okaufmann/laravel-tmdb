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
namespace Okaufmann\LaravelTmdb\Factory\Common;

use Okaufmann\LaravelTmdb\Factory\AbstractFactory;
use Okaufmann\LaravelTmdb\Model\Collection\Videos;
use Okaufmann\LaravelTmdb\Model\Common\Video;

/**
 * Class VideoFactory
 * @package Tmdb\Factory\Common
 */
class VideoFactory extends AbstractFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(array $data = [])
    {
        $videoType = $this->resolveVideoType($data);

        return (null === $videoType) ? null : $this->hydrate($videoType, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection(array $data = [])
    {
        $collection = new Videos();

        if (array_key_exists('videos', $data)) {
            $data = $data['videos'];
        }

        if (array_key_exists('results', $data)) {
            $data = $data['results'];
        }

        foreach ($data as $item) {
            $collection->add(null, $this->create($item));
        }

        return $collection;
    }

    private function resolveVideoType($data)
    {
        if (array_key_exists('site', $data) && !empty($data['site'])) {
            $site = strtolower($data['site']);

            switch ($site) {
                case 'youtube':
                    return new Video\Youtube();
                default:
                    return new Video();
            }
        }
    }
}
