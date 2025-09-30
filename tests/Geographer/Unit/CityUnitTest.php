<?php

declare(strict_types=1);

namespace Tests;

use ALameLlama\Geographer\City;

class CityUnitTest extends Test
{
    /**
     * @test
     */
    public function city_can_be_instantiated_based_on_code(): void
    {
        $city = City::build(2761369);
        $this->assertInstanceOf(City::class, $city);
        $this->assertNotEmpty($city->getName());
    }
}
