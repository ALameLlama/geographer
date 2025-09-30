<?php

declare(strict_types=1);

namespace ALameLlama\Geographer;

use ALameLlama\Geographer\Collections\MemberCollection;
use ALameLlama\Geographer\Contracts\IdentifiableInterface;
use ALameLlama\Geographer\Contracts\ManagerInterface;
use ALameLlama\Geographer\Services\DefaultManager;
use ALameLlama\Geographer\Traits\ExposesFields;
use ALameLlama\Geographer\Traits\HasCollection;
use ALameLlama\Geographer\Traits\HasManager;
use ArrayAccess;

use function call_user_func;

/**
 * Class Divisible
 */
abstract class Divisible implements ArrayAccess, IdentifiableInterface
{
    use ExposesFields, HasCollection, HasManager;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var MemberCollection
     */
    protected $members;

    /**
     * @var string
     */
    protected $memberClass;

    /**
     * @var string
     */
    protected static $parentClass;

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var string
     */
    protected $standard;

    /**
     * @var array
     */
    protected $exposed = [];

    /**
     * @var Divisible
     */
    private $parent;

    /**
     * Country constructor.
     *
     * @param  string  $parentCode
     */
    public function __construct(array $meta = [], protected $parentCode = null, ?ManagerInterface $manager = null)
    {
        $this->meta = $meta;
        $this->manager = $manager instanceof ManagerInterface ? $manager : new DefaultManager;
    }

    /**
     * @param  int|string  $id
     * @param  ManagerInterface  $config
     * @return City|Country|State
     */
    public static function build($id, $config = null)
    {
        $config = $config ?: new DefaultManager;
        $meta = $config->getRepository()->indexSearch($id, static::$parentClass);
        $parent = $meta['parent'] ?? null;

        return new static($meta, $parent, $config);
    }

    /**
     * @return MemberCollection
     */
    public function getMembers()
    {
        if (! $this->members) {
            $this->loadMembers();
        }

        return $this->members;
    }

    /**
     * Best effort name
     *
     * @param  string  $locale
     * @return string
     */
    public function getName($locale = null)
    {
        if ($locale) {
            $this->setLocale($locale);
        }

        return $this->manager->expectsLongNames() ? $this->getLongName() : $this->getShortName();
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        $this->manager->useShortNames();

        return $this->translate();
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        $this->manager->useLongNames();

        return $this->translate();
    }

    /**
     * @return bool
     */
    public function expectsLongNames()
    {
        return $this->manager->expectsLongNames();
    }

    /**
     * @return Divisible
     */
    public function parent()
    {
        if (! $this->parent) {
            $this->parent = call_user_func([static::$parentClass, 'build'], $this->parentCode, $this->manager);
        }

        return $this->parent;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return string|int
     */
    public function getParentCode()
    {
        return $this->meta['parent'];
    }

    /**
     * @param  string  $locale
     * @return string
     */
    public function translate($locale = null)
    {
        if ($locale) {
            $this->manager->setLocale($locale);
        }

        return $this->manager->getTranslator()->translate($this, $this->manager->getLocale());
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        $codes = [];
        array_walk_recursive($this->meta['ids'], function ($id) use (&$codes): void {
            $codes[] = $id;
        });

        return $codes;
    }

    /**
     * @return void
     */
    protected function loadMembers(?MemberCollection $collection = null)
    {
        $standard = $this->standard ?: $this->manager->getStandard();

        $data = $this->manager->getRepository()->getData(static::class, [
            'code' => $this->getCode(),
            'parentCode' => $this->getParentCode(),
        ]);

        $collection = $collection instanceof MemberCollection ? $collection : new MemberCollection($this->manager);

        foreach ($data as $meta) {
            $entity = new $this->memberClass($meta, $this->getCode(), $this->manager);

            if (! empty($entity[$standard . 'Code'])) {
                $collection->add($entity, $entity[$standard . 'Code']);
            }
        }

        $this->members = $collection;
    }
}
