<?php

namespace ALameLlama\Geographer;

/**
 * Class City
 * @package ALamewLLama\Geographer
 *
 * @method string getCode()
 * @method mixed findOneByCode($code)
 * @method string getGeonamesCode()
 * @method mixed findOneByGeonamesCode($geonamesCode)
 * @method string getName()
 * @method mixed findOneByName($name)
 * @method float getLatitude()
 * @method mixed findOneByLatitude($latitude)
 * @method float getLongitude()
 * @method mixed findOneByLongitude($longitude)
 * @method int getPopulation()
 * @method mixed findOneByPopulation($population)
 * @method bool getCapital()
 * @method mixed findOneByCapital($capital)
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
        'capital',
    ];
}
