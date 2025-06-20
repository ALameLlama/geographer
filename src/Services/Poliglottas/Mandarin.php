<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Mandarin
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class Mandarin extends Base
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
        'in' => '在'
    ];
}
