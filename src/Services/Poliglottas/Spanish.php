<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

/**
 * Class Spanish
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
 */
class Spanish extends Base
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
        'in' => 'en'
    ];
}
