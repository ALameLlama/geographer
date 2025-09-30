<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Ukrainian
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
        'in' => 'в',
    ];
}
