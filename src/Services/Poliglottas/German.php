<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class German
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class German extends Base
{
    /**
     * @var string
     */
    protected $code = 'de';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'aus',
        'in' => 'in'
    ];
}
