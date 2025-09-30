<?php

declare(strict_types=1);

namespace ALameLlama\Geographer;

/**
 * Class State
 *
 * @method string getCode()
 * @method mixed findOneByCode($code)
 * @method string getFipsCode()
 * @method mixed findOneByFipsCode($fipsCode)
 * @method string getIsoCode()
 * @method mixed findOneByIsoCode($isoCode)
 * @method string getGeonamesCode()
 * @method mixed findOneByGeonamesCode($geonamesCode)
 * @method string getPostCodes()
 * @method mixed findOneByPostCode($postCode)
 * @method string getName()
 * @method mixed findOneByName($name)
 * @method string getTimezone()
 * @method mixed findOneByTimezone($timezone)
 */
class State extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = City::class;

    /**
     * @var string
     */
    protected static $parentClass = Country::class;

    /**
     * @var string
     */
    protected $standard = 'geonames';

    /**
     * @var array
     */
    protected $exposed = [
        'code' => 'ids.geonames',
        'fipsCode' => 'ids.fips',
        'isoCode' => 'ids.iso_3166_2',
        'geonamesCode' => 'ids.geonames',
        'postCodes' => 'postcodes',
        'name',
        'timezone',
    ];

    /**
     * @return Collections\MemberCollection
     */
    public function getCities()
    {
        return $this->getMembers();
    }
}
