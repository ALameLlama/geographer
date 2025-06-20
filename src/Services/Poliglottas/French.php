<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class French
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class French extends Base
{
    /**
     * @var string
     */
    protected $code = 'fr';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'de',
        'in' => 'à'
    ];
}
