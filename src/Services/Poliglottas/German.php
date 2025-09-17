<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class German
 */
final class German extends Base
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
        'in' => 'in',
    ];
}
