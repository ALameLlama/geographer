<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Contracts;

/**
 * Interface TranslationRepositoryInterface
 */
interface TranslationAgencyInterface
{
    /**
     * @param  string  $language
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $language);

    /**
     * @return RepositoryInterface $repository
     */
    public function getRepository();

    /**
     * @param  RepositoryInterface  $repository
     * @return TranslationAgencyInterface
     */
    public function setRepository($repository);

    /**
     * @return array
     */
    public function getSupportedLanguages();

    /**
     * @return TranslationAgencyInterface
     */
    public function setForm($form);

    /**
     * @return TranslationAgencyInterface
     */
    public function includePrepositions();

    /**
     * @return TranslationAgencyInterface
     */
    public function excludePrepositions();
}
