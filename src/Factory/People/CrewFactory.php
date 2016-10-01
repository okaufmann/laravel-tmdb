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
namespace Okaufmann\LaravelTmdb\Factory\People;

use Okaufmann\LaravelTmdb\Factory\PeopleFactory;
use Okaufmann\LaravelTmdb\Model\Collection\People\Crew;

/**
 * Class CrewFactory
 * @package Tmdb\Factory\People
 */
class CrewFactory extends PeopleFactory
{
    /**
     * {@inheritdoc}
     * @param \Okaufmann\LaravelTmdb\Model\Person\CrewMember $person
     */
    public function createCollection(array $data = [], $person = null)
    {
        $collection = new Crew();

        if (is_object($person)) {
            $class = get_class($person);
        } else {
            $class = '\Okaufmann\LaravelTmdb\Model\Person\CrewMember';
        }

        foreach ($data as $item) {
            $collection->add(null, $this->create($item, new $class()));
        }

        return $collection;
    }
}
