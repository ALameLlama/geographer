<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Contracts;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * @param  string  $class
     * @return array
     */
    public function getData($class, array $params);

    /**
     * @return array
     */
    public function getTranslations(IdentifiableInterface $subject, $language);

    /**
     * @return mixed
     */
    public function indexSearch($id, $class);
}
