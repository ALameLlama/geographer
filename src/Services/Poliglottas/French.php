<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class French
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
        'in' => 'à',
    ];
}
