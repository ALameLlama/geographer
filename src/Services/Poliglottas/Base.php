<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Services\Poliglottas;

use ALameLlama\Geographer\Contracts\IdentifiableInterface;
use ALameLlama\Geographer\Contracts\PoliglottaInterface;
use ALameLlama\Geographer\Contracts\TranslationAgencyInterface;
use ALameLlama\Geographer\Exceptions\FileNotFoundException;
use ALameLlama\Geographer\Exceptions\MisconfigurationException;

/**
 * Class Base
 */
abstract class Base implements PoliglottaInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $defaultPrepositions = [];

    /**
     * Base constructor.
     */
    public function __construct(protected TranslationAgencyInterface $agency) {}

    /**
     * @param  string  $form
     * @param  bool  $preposition
     * @return string
     *
     * @throws MisconfigurationException
     */
    public function translate(IdentifiableInterface $subject, $form = 'default', $preposition = true)
    {
        if (! method_exists($this, 'inflict' . ucfirst($form))) {
            throw new MisconfigurationException('Language ' . $this->code . ' doesn\'t inflict to ' . $form);
        }

        $meta = $this->fromDictionary($subject);
        $result = $this->extract($meta, $subject->expectsLongNames(), $form, true);

        if ($result && $preposition) {
            return $result;
        }
        if ($result && ! $preposition) {
            return mb_substr($result, mb_strpos($result, ' '));
        }

        $result = $this->inflictDefault($meta, $subject->expectsLongNames());
        if ($form === 'default') {
            return $result;
        }

        $result = $this->{'inflict' . ucfirst($form)}($result);
        if ($preposition) {
            return $this->getPreposition($form, $result) . ' ' . $result;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function fromDictionary(IdentifiableInterface $subject)
    {
        try {
            $translations = $this->agency->getRepository()->getTranslations($subject, $this->code);
        } catch (FileNotFoundException) {
            return $subject->getMeta();
        }

        return $translations ?: $subject->getMeta();
    }

    /**
     * @return string
     */
    protected function inflictDefault(array $meta, $long)
    {
        return $this->extract($meta, $long, 'default', true);
    }

    /**
     * @param  string  $template
     * @return string
     */
    protected function inflictIn($template)
    {
        return $template;
    }

    /**
     * @param  string  $template
     * @return string
     */
    protected function inflictFrom($template)
    {
        return $template;
    }

    /**
     * @param  string  $result
     * @return string
     */
    protected function getPreposition($form, $result = null)
    {
        return $this->defaultPrepositions[$form];
    }

    /**
     * @param  bool  $long
     * @param  bool  $fallback
     * @return mixed
     */
    protected function extract(array $meta, $long, $form, $fallback = false)
    {
        $variants = [];
        $keys = $long ? ['long', 'short '] : ['short', 'long'];

        if (! isset($meta[$keys[0]][$form]) && ! $fallback) {
            return false;
        }

        if (isset($meta[$keys[0]][$form])) {
            $variants[] = $meta[$keys[0]][$form];
        }

        if (isset($meta[$keys[1]][$form])) {
            $variants[] = $meta[$keys[1]][$form];
        }

        return $variants === [] ? false : $variants[0];
    }
}
