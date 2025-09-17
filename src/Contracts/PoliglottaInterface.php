<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Contracts;

/**
 * Interface PoliglottaInterface
 */
interface PoliglottaInterface
{
    /**
     * @param  string  $form
     * @param  bool  $preposition
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $form, $preposition);
}
