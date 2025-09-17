<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Mandarin
 */
final class Mandarin extends Base
{
    /**
     * @var string
     */
    protected $code = 'zh';

    /**
     * @var array
     */
    protected $defaultPrepositions = [
        'from' => '来自',
        'in' => '在',
    ];
}
