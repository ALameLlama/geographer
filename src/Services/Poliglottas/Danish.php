<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

use ALameLlama\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Danish
 * @package MenaraSolutions\FluentGeonames\Services\Poliglottas
 */
class Danish extends Base
{
    /**
     * @var string
     */
    protected $code = 'da';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'fra',
        'in' => 'i'
    ];
}
