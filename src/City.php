<?php

namespace ALameLlama\Geographer;

/**
 * Class City
 * @package ALamewLLama\Geographer
 */
class City extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = null;

    /**
     * @var string
     */
    protected static $parentClass = State::class;

    /**
     * @var array
     */
    protected $exposed = [
        'code' => 'ids.geonames',
        'geonamesCode' => 'ids.geonames',
        'name',
        'latitude' => 'lat',
        'longitude' => 'lng',
        'population',
        'capital'
    ];
}
