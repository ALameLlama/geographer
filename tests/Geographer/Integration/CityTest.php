<?php

declare(strict_types=1);

namespace Tests;

use ALameLlama\Geographer\City;
use ALameLlama\Geographer\Earth;
use ALameLlama\Geographer\State;

use function in_array;
use function is_int;
use function is_string;

final class CityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Countries that don't have any big cities
     */
    private array $emptyCountries = [
        'AX', 'AS', 'AD', 'AI', 'AQ', 'AG', 'AW', 'BM', 'BQ', 'BV', 'IO', 'KY', 'CX', 'CC', 'KM',
        'CK', 'CW', 'DM', 'FK', 'FO', 'PF', 'TF', 'GI', 'GL', 'GD', 'GU', 'GG', 'HM', 'VA', 'IM',
        'JE', 'KI', 'LI', 'MT', 'MH', 'FM', 'MC', 'MS', 'NR', 'NU', 'NF', 'MP', 'PW', 'PN', 'BL',
        'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 'SM', 'SC', 'SX', 'GS', 'SJ', 'TK', 'TO', 'TC',
        'TV', 'UM', 'VU', 'VG', 'WF', 'GF', 'HK', 'MO', 'YT', 'NC', 'PS', 'PR', 'RE', 'VI', 'EH',
        'BS', 'MQ', 'GP',
    ];

    /**
     * @test
     */
    public function most_states_of_all_countries_have_cities(): void
    {
        $planet = new Earth;
        $countries = $planet->getCountries();

        foreach ($countries as $country) {
            $citiesCount = 0;

            foreach ($country->getStates() as $state) {
                $cities = $state->getCities();

                foreach ($cities as $city) {
                    $citiesCount++;
                    $array = $city->toArray();
                    $this->assertTrue(isset($array['code']) && is_int($array['code']));
                    $this->assertTrue(isset($array['name']) && is_string($array['name']));

                    if ($country->getCode() === 'RU') {
                        // $city->setLanguage('ru')->inflict('from');
                        // echo $city->getName() . "\n";
                    }
                }
            }

            if (! in_array($country->getCode(), $this->emptyCountries)) {
                // if ($citiesCount == 0) echo $country->getCode();
                // if ($citiesCount == 0) echo count($country->getStates());
                $this->assertTrue($citiesCount > 0);
            }
        }
    }

    /**
     * @test
     */
    public function city_can_get_a_parent(): void
    {
        $city = City::build(2761369);
        $state = $city->parent();
        $this->assertInstanceOf(State::class, $state);
    }
}
