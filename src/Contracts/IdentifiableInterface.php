<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Contracts;

/**
 * Interface IdentifiableInterface
 */
interface IdentifiableInterface
{
    /**
     * @return bool
     */
    public function expectsLongNames();

    /**
     * @return array
     */
    public function getMeta();

    /**
     * Get an array of unique identification codes for this object
     *
     * @return array
     */
    public function getCodes();
}
