<?php

namespace ALameLlama\Geographer\Services\Poliglottas;

use ALameLlama\Geographer\Contracts\IdentifiableInterface;

/**
 * Class Italian
 * @package ALameLlama\FluentGeonames\Services\Poliglottas
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
        'in' => 'in'
    ];
}
