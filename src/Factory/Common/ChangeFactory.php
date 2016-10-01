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
use Okaufmann\LaravelTmdb\Model\Common\Change;
use Okaufmann\LaravelTmdb\Model\Common\GenericCollection;

/**
 * Class ChangeFactory
 * @package Tmdb\Factory\Common
 */
class ChangeFactory extends AbstractFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(array $data = [])
    {
        $change = new Change();

        if (array_key_exists('items', $data)) {
            $items = new GenericCollection();

            foreach ($data['items'] as $item) {
                $item = $this->createChangeItem($item);

                $items->add(null, $item);
            }

            $change->setItems($items);
        }

        return $this->hydrate($change, $data);
    }

    /**
     * Create individual change items
     *
     * @param  array                     $data
     * @return \Okaufmann\LaravelTmdb\Model\AbstractModel
     */
    private function createChangeItem(array $data = [])
    {
        return $this->hydrate(new Change\Item(), $data);
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection(array $data = [])
    {
        $collection = new GenericCollection();

        if (array_key_exists('changes', $data)) {
            $data = $data['changes'];
        }

        foreach ($data as $item) {
            $collection->add(null, $this->create($item));
        }

        return $collection;
    }
}
