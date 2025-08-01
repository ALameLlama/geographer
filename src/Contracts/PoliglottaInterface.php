<?php

namespace ALameLlama\Geographer\Contracts;

/**
 * Interface PoliglottaInterface
 * @package ALameLlama\FluentGeonames\Contracts
 */
interface PoliglottaInterface
{
    /**
     * @param IdentifiableInterface $subject
     * @param string $form
     * @param bool $preposition
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form, $preposition);
}
