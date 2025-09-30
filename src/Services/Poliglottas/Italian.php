<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Italian
 */
class Italian extends Base
{
    /**
     * @var string
     */
    protected $code = 'it';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'da',
        'in' => 'in',
    ];
}
