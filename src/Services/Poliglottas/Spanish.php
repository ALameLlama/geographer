<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Spanish
 */
final class Spanish extends Base
{
    /**
     * @var string
     */
    protected $code = 'es';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => 'de',
        'in' => 'en',
    ];
}
