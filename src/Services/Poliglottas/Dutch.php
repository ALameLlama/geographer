<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Dutch
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class Dutch extends Base
{
    /**
     * @var string
     */
    protected $code = 'nl';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'van',
        'in' => 'in'
    ];
}
