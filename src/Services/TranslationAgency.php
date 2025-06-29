<?php

namespace ALameLlama\Geographer\Services;

use ALameLlama\Geographer\Contracts\IdentifiableInterface;
use ALameLlama\Geographer\Contracts\PoliglottaInterface;
use ALameLlama\Geographer\Contracts\RepositoryInterface;
use ALameLlama\Geographer\Contracts\TranslationAgencyInterface;
use ALameLlama\Geographer\Exceptions\MisconfigurationException;
use ALameLlama\Geographer\Services\Poliglottas\Danish;
use ALameLlama\Geographer\Services\Poliglottas\Dutch;
use ALameLlama\Geographer\Services\Poliglottas\French;
use ALameLlama\Geographer\Services\Poliglottas\German;
use ALameLlama\Geographer\Services\Poliglottas\Mandarin;
use ALameLlama\Geographer\Services\Poliglottas\Russian;
use ALameLlama\Geographer\Services\Poliglottas\English;
use ALameLlama\Geographer\Services\Poliglottas\Spanish;
use ALameLlama\Geographer\Services\Poliglottas\Italian;
use ALameLlama\Geographer\Services\Poliglottas\Ukrainian;

/**
 * Class TranslationAgency
 * @package ALameLlama\FluentGeonames\Services
 */
class TranslationAgency implements TranslationAgencyInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $form = 'default';

    /**
     * @var array
     */
    protected $inflictsTo = [];

    /**
     * @var bool
     */
    protected $prepositions = true;

    /**
     * Constants for available languages
     */
    const LANG_RUSSIAN = 'ru';
    const LANG_ENGLISH = 'en';
    const LANG_SPANISH = 'es';
    const LANG_ITALIAN = 'it';
    const LANG_FRENCH = 'fr';
    const LANG_CHINESE = 'zh';
    const LANG_UKRAINIAN = 'uk';
    const LANG_GERMAN = 'de';
    const LANG_DUTCH = 'nl';
    const LANG_DANISH = 'da';

    /**
     * Constants for available forms
     */
    const FORM_DEFAULT = 'default';
    const FORM_IN = 'in';
    const FORM_FROM = 'from';

    /**
     * List of available translators
     *
     * @var array
     */
    protected $languages = [
        self::LANG_RUSSIAN => Russian::class,
        self::LANG_ENGLISH => English::class,
        self::LANG_SPANISH => Spanish::class,
        self::LANG_ITALIAN => Italian::class,
        self::LANG_FRENCH => French::class,
        self::LANG_CHINESE => Mandarin::class,
        self::LANG_UKRAINIAN => Ukrainian::class,
        self::LANG_GERMAN => German::class,
        self::LANG_DUTCH => Dutch::class,
        self::LANG_DANISH => Danish::class,
    ];

    /**
     * @var array PoliglottaInterface
     */
    protected $translators = [];

    /**
     * TranslationRepository constructor.
     * @param string $basePath
     * @param RepositoryInterface $repository
     */
    public function __construct($basePath, RepositoryInterface $repository)
    {
        $this->basePath = $basePath;
        $this->repository = $repository;
    }

    /**
     * @param string $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return $this
     */
    public function includePrepositions()
    {
        $this->prepositions = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function excludePrepositions()
    {
        $this->prepositions = false;

        return $this;
    }

    /**
     * @param IdentifiableInterface $subject
     * @param string $language
     * @return string
     */
    public function translate(IdentifiableInterface $subject, $language = 'en')
    {
        $translator = $this->getTranslator($language);

        return $translator->translate($subject, $this->form, $this->prepositions);
    }

    /**
     * @param string $language
     * @return PoliglottaInterface
     * @throws MisconfigurationException
     */
    public function getTranslator($language)
    {
        if (! isset($this->languages[$language])) {
            throw new MisconfigurationException('No hablo ' . $language . ', sorry');
        }

        if (! isset($this->translators[$language])) {
            $this->translators[$language] = new $this->languages[$language]($this);
        }

        return $this->translators[$language];
    }

    /**
     * @return RepositoryInterface $repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     * @return TranslationAgencyInterface
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return array
     */
    public function getSupportedLanguages()
    {
        return array_keys($this->translators);
    }
}
