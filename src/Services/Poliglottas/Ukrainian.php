<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Ukrainian
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class Ukrainian extends Base
{
    /**
     * @var string
     */
    protected $code = 'uk';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'з',
        'in' => 'в'
    ];
}
