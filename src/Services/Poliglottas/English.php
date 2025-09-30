<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

use ALameLlama\Geographer\Contracts\IdentifiableInterface;
use ALameLlama\Geographer\Contracts\PoliglottaInterface;
use ALameLlama\Geographer\Exceptions\MisconfigurationException;

/**
 * Class English
 */
class English implements PoliglottaInterface
{
    private array $defaultPrepositions = [
        'from' => 'from',
        'in' => 'in',
    ];

    private string $code = 'en';

    /**
     * @param  string  $form
     * @param  bool  $preposition
     * @return string
     *
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $preposition = true)
    {
        if ($form !== 'default' && ! isset($this->defaultPrepositions[$form])) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $result = $subject->expectsLongNames() ? $this->getLongName($subject->getMeta()) : $this->getShortName($subject->getMeta());

        if ($preposition && $form !== 'default') {
            return $this->defaultPrepositions[$form] . ' ' . $result;
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getLongName(array $meta)
    {
        return isset($meta['long']) ? $meta['long']['default'] : $meta['short']['default'];
    }

    /**
     * @return string
     */
    private function getShortName(array $meta)
    {
        return isset($meta['short']) ? $meta['short']['default'] : $meta['long']['default'];
    }
}
