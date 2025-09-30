<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services;

use const DIRECTORY_SEPARATOR;

use ALameLlama\Geographer\Contracts\ManagerInterface;
use ALameLlama\Geographer\Contracts\RepositoryInterface;
use ALameLlama\Geographer\Contracts\TranslationAgencyInterface;
use ALameLlama\Geographer\Repositories\File;

use function dirname;

/**
 * Class DefaultManager
 */
class DefaultManager implements ManagerInterface
{
    /**
     * Supported subdivision standards
     */
    const STANDARD_ISO = 'iso';

    const STANDARD_FIPS = 'fips';

    const STANDARD_GEONAMES = 'geonames';

    private TranslationAgencyInterface $translator;

    private RepositoryInterface $repository;

    /**
     * @var string
     */
    private $language = 'en';

    /**
     * @var string
     */
    private $standard = self::STANDARD_ISO;

    private bool $brief = true;

    private bool $prepositions = true;

    /**
     * @var string
     */
    private $path;

    /**
     * DefaultConfig constructor.
     *
     * @param  string  $path
     */
    public function __construct($path = null, ?TranslationAgencyInterface $translator = null, ?RepositoryInterface $repository = null)
    {
        $this->path = $path ?: self::getDefaultPrefix();
        $this->repository = $repository instanceof RepositoryInterface ? $repository : new File;
        $this->translator = $translator instanceof TranslationAgencyInterface ? $translator : new TranslationAgency($this->path, $this->repository);
    }

    public static function getDefaultPrefix(): string
    {
        return dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getStoragePath()
    {
        return $this->path;
    }

    /**
     * @param  string  $path
     * @return $this
     */
    public function setStoragePath($path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getTranslator(): TranslationAgencyInterface
    {
        $this->prepositions ? $this->translator->includePrepositions() : $this->translator->excludePrepositions();

        return $this->translator;
    }

    /**
     * @return $this
     */
    public function setTranslator(TranslationAgencyInterface $translator): static
    {
        $this->translator = $translator;

        return $this;
    }

    public function getRepository(): RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository): static
    {
        $this->repository = $repository;
        $this->translator->setRepository($repository);

        return $this;
    }

    /**
     * @return $this
     */
    public function setLocale($locale): static
    {
        $this->language = strtolower(substr($locale, 0, 2));

        return $this;
    }

    /**
     * @param  string  $form
     * @return $this
     */
    public function setForm($form): static
    {
        $this->translator->setForm($form);

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->language;
    }

    /**
     * @return $this
     */
    public function useLongNames(): static
    {
        $this->brief = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function useShortNames(): static
    {
        $this->brief = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function includePrepositions(): static
    {
        $this->prepositions = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function excludePrepositions(): static
    {
        $this->prepositions = false;

        return $this;
    }

    public function expectsLongNames(): bool
    {
        return ! $this->brief;
    }

    /**
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param  string  $standard
     * @return $this
     */
    public function setStandard($standard): static
    {
        $this->standard = $standard;

        return $this;
    }
}
