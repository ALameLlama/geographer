<?php

declare(strict_types=1);

namespace ALameLlama\Geographer;

use ALameLlama\Geographer\Collections\MemberCollection;
use ALameLlama\Geographer\Services\DefaultManager;

/**
 * Class Country
 *
 * @method string getCode()
 * @method mixed findOneByCode($code)
 * @method string getCode3()
 * @method mixed findOneByCode3($code3)
 * @method string getIsoCode()
 * @method mixed findOneByIsoCode($isoCode)
 * @method string getNumericCode()
 * @method mixed findOneByNumericCode($numericCode)
 * @method string getGeonamesCode()
 * @method mixed findOneByGeonamesCode($geonamesCode)
 * @method string getFipsCode()
 * @method mixed findOneByFipsCode($fipsCode)
 * @method float getArea()
 * @method mixed findOneByArea($area)
 * @method string getCurrency()
 * @method mixed findOneByCurrency($currency)
 * @method string getPhonePrefix()
 * @method mixed findOneByPhonePrefix($phonePrefix)
 * @method string getMobileFormat()
 * @method mixed findOneByMobileFormat($mobileFormat)
 * @method string getLandlineFormat()
 * @method mixed findOneByLandlineFormat($landlineFormat)
 * @method string getTrunkPrefix()
 * @method mixed findOneByTrunkPrefix($trunkPrefix)
 * @method int getPopulation()
 * @method mixed findOneByPopulation($population)
 * @method string getContinent()
 * @method mixed findOneByContinent($continent)
 * @method string getLanguage()
 * @method mixed findOneByLanguage($language)
 * @method string getName()
 * @method mixed findOneByName($name)
 */
final class Country extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = State::class;

    /**
     * @var string
     */
    protected static $parentClass = Earth::class;

    /**
     * @var array
     */
    protected $exposed = [
        'code' => 'ids.iso_3166_1.0',
        'code3' => 'ids.iso_3166_1.1',
        'isoCode' => 'ids.iso_3166_1.0',
        'numericCode' => 'ids.iso_3166_1.2',
        'geonamesCode' => 'ids.geonames',
        'fipsCode' => 'ids.fips',
        'area',
        'currency',
        'phonePrefix' => 'phone',
        'mobileFormat',
        'landlineFormat',
        'trunkPrefix',
        'population',
        'continent',
        'language' => 'languages.0',
        'name',
    ];

    /**
     * {@inheritdoc}
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultManager;
        $earth = (new Earth)->setManager($config);

        return $earth->findOneByCode($id);
    }

    public function getParentCode(): string
    {
        return 'SOL-III';
    }

    /**
     * @return bool|Divisible
     */
    public function getCapital()
    {
        foreach ($this->getStates() as $state) {
            if (
                $capital = $state->findOne([
                    'capital' => true,
                ])
            ) {
                return $capital;
            }
        }

        return null;
    }

    /**
     * @return MemberCollection
     */
    public function getStates()
    {
        return $this->getMembers();
    }
}
