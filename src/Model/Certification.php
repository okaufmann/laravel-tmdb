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

use Okaufmann\LaravelTmdb\Model\Common\GenericCollection;

/**
 * Class Certification
 * @package Tmdb\Model
 */
class Certification extends AbstractModel
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var GenericCollection
     */
    private $certifications;

    public static $properties = [
        'country',
    ];

    public function __construct()
    {
        $this->certifications  = new GenericCollection();
    }

    /**
     * @param  \Okaufmann\LaravelTmdb\Model\Common\GenericCollection $certifications
     * @return $this
     */
    public function setCertifications($certifications)
    {
        $this->certifications = $certifications;

        return $this;
    }

    /**
     * @return \Okaufmann\LaravelTmdb\Model\Common\GenericCollection
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * @param  string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
