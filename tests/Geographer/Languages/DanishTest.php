<?php

declare(strict_types=1);

namespace Tests\Languages;

use ALameLlama\Geographer\Earth;
use Tests\Test;

final class DanishTest extends Test
{
    private string $languageCode = 'da';

    /**
     * @test
     */
    public function all_countries_have_translated_names(): void
    {
        $earth = new Earth;
        $countries = $earth->getCountries();

        foreach ($countries as $country) {
            $this->assertNotEquals($country->setLocale($this->languageCode)->getName(), $country->setLocale('en')->getName());
        }
    }
}
