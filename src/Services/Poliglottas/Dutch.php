<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Dutch
 */
final class Dutch extends Base
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
        'in' => 'in',
    ];
}
