<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Danish
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
        'in' => 'i',
    ];
}
