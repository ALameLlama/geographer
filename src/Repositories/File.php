<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Repositories;

use const DIRECTORY_SEPARATOR;

use ALameLlama\Geographer\City;
use ALameLlama\Geographer\Contracts\IdentifiableInterface;
use ALameLlama\Geographer\Contracts\RepositoryInterface;
use ALameLlama\Geographer\Country;
use ALameLlama\Geographer\Earth;
use ALameLlama\Geographer\Exceptions\FileNotFoundException;
use ALameLlama\Geographer\Exceptions\MisconfigurationException;
use ALameLlama\Geographer\Exceptions\ObjectNotFoundException;
use ALameLlama\Geographer\Helpers\WhereAmI;
use ALameLlama\Geographer\State;

use function dirname;
use function in_array;

class File implements RepositoryInterface
{
    /**
     * Path to resource files
     *
     * @var string
     */
    private $prefix;

    /**
     * Path to translation files
     *
     * @var string
     */
    private $translationsPrefix;

    private static array $paths = [
        Earth::class => 'countries.json',
        Country::class => 'states/code.json',
        State::class => 'cities/parentCode.json',
    ];

    private static array $indexes = [
        Country::class => 'indexCountry.json',
        State::class => 'indexState.json',
    ];

    private array $cache = [];

    /**
     * File constructor.
     *
     * @param  string  $prefix
     * @param  string  $translationsPrefix
     */
    public function __construct($prefix = null, $translationsPrefix = null)
    {
        $this->prefix = $prefix ?: $this->getDefaultPath();
        $this->translationsPrefix = $translationsPrefix ?: $this->guessTranslationsPrefix();
    }

    /**
     * @return string
     *
     * @throws MisconfigurationException
     */
    public function getPath(string $class, string $prefix, array $params)
    {
        if (! isset(self::$paths[$class])) {
            throw new MisconfigurationException($class . ' is not supposed to load data');
        }

        return $prefix . DIRECTORY_SEPARATOR . str_replace(array_keys($params), array_values($params), self::$paths[$class]);
    }

    /**
     * @return string
     */
    public function getTranslationsPrefix()
    {
        return $this->translationsPrefix;
    }

    /**
     * @param  string  $prefix
     * @return $this
     */
    public function setTranslationsPrefix($prefix): static
    {
        $this->translationsPrefix = $prefix;

        return $this;
    }

    public function guessTranslationsPrefix(): string
    {
        if (is_dir(dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'menarasolutions')) {
            return dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'menarasolutions';
        }

        if (is_dir(dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'alamellama')) {
            return dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'alamellama';
        }

        // By default, we assume that language package was installed using Composer
        return dirname(__FILE__, 4);
    }

    public function getTranslationsPath(IdentifiableInterface $subject, string $language): string
    {
        $elements = explode('\\', $subject::class);
        $key = strtolower(end($elements));
        $root = $this->translationsPrefix . DIRECTORY_SEPARATOR . 'geographer-' . $language;

        if ($subject::class === City::class) {
            $country = $subject->getMeta()['country'];

            return $root . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $country . '.json';
        }

        return $root . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . 'all.json';
    }

    /**
     * @param  string  $prefix
     */
    public function setPrefix($prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return array
     */
    public function getData($class, array $params)
    {
        $file = $this->getPath($class, $this->prefix, $params);

        try {
            $data = self::loadJson($file);
        } catch (FileNotFoundException) {
            // Some divisions don't have data files, so we don't want to throw the exception
            return [];
        }

        // ToDo: remove this logic from here
        if ($class === State::class && isset($params['code'])) {
            foreach ($data as $key => $meta) {
                if ($meta['parent'] !== $params['code']) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * @param  int  $id
     * @param  string  $class
     * @return array
     *
     * @throws ObjectNotFoundException
     */
    public function indexSearch($id, $class)
    {
        $code = $this->getCodeFromIndex($this->prefix . DIRECTORY_SEPARATOR . self::$indexes[$class], (string) $id);

        $key = ($class === State::class) ? 'parentCode' : 'code';
        $path = self::getPath($class, $this->prefix, [$key => $code]);

        if (! isset($this->cache[$path])) {
            $this->cache[$path] = $this->loadJson($path);
        }

        foreach ($this->cache[$path] as $member) {
            if (in_array($id, $member['ids'])) {
                return $member;
            }
        }

        throw new ObjectNotFoundException('Cannot find meta for division ' . $id);
    }

    /**
     * @return array
     *
     * @throws ObjectNotFoundException
     * @throws FileNotFoundException
     * @throws MisconfigurationException
     */
    public function loadJson(string $path)
    {
        if (! file_exists($path)) {
            throw new FileNotFoundException('File not found: ' . $path);
        }
        $decoded = json_decode(file_get_contents($path), true);
        if ($decoded === null) {
            throw new MisconfigurationException('Unable to decode JSON for ' . $path);
        }

        return $decoded;
    }

    /**
     * @return array
     */
    public function getTranslations(IdentifiableInterface $subject, $language)
    {
        $path = $this->getTranslationsPath($subject, $language);
        if (empty($this->cache[$path])) {
            $this->loadTranslations($path);
        }

        foreach ($subject->getCodes() as $code) {
            if (isset($this->cache[$path][$code])) {
                return $this->cache[$path][$code];
            }
        }

        return null;
    }

    /**
     * @return string
     *
     * @throws FileNotFoundException
     */
    private function getDefaultPath()
    {
        if (! class_exists(WhereAmI::class)) {
            throw new FileNotFoundException('Unable to locate data package');
        }

        return WhereAmI::path();
    }

    /**
     * @return mixed
     *
     * @throws ObjectNotFoundException
     */
    private function getCodeFromIndex(string $path, string $id)
    {
        if (preg_match('/[A-Z]{2}-[A-Z0-9]{1,3}/', $id) === 1) {
            return substr($id, 0, 2);
        }

        if (! isset($this->cache[$path])) {
            $this->cache[$path] = $this->loadJson($path);
        }

        if (! isset($this->cache[$path][$id])) {
            throw new ObjectNotFoundException('Cannot find object with id ' . $id);
        }

        return $this->cache[$path][$id];
    }

    /**
     * @throws FileNotFoundException
     */
    private function loadTranslations(string $path): void
    {
        $meta = $this->loadJson($path);

        foreach ($meta as $one) {
            $this->cache[$path][$one['code']] = $one;
        }
    }
}
