<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Collections;

use const SORT_REGULAR;

use ALameLlama\Geographer\Collections\Traits\ImplementsArray;
use ALameLlama\Geographer\Contracts\ManagerInterface;
use ALameLlama\Geographer\Divisible;
use ALameLlama\Geographer\Exceptions\ObjectNotFoundException;
use ALameLlama\Geographer\Traits\HasManager;
use ArrayObject;

use function array_slice;

/**
 * Class MemberCollection
 */
final class MemberCollection extends ArrayObject
{
    use HasManager, ImplementsArray;

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var array
     */
    private $divisions = [];

    /**
     * MemberCollection constructor.
     *
     * @param  array  $divisions
     */
    public function __construct(ManagerInterface $config, $divisions = [])
    {
        parent::__construct();

        $this->manager = $config;
        $this->divisions = $divisions;
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->divisions as $division) {
            $array[] = $division->toArray();
        }

        return $array;
    }

    /**
     * @param  string  $key
     */
    public function pluck($key): array
    {
        return array_map(fn (array $division) => $division[$key] ?? null, $this->toArray());
    }

    /**
     * @param $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return array_map(function($division) use ($keys) {
            return array_intersect_key($division, array_flip($keys));
        }, $this->toArray());
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->divisions);
    }

    /**
     * @return mixed
     *
     * @throws ObjectNotFoundException
     */
    public function get($key)
    {
        if (! isset($this->divisions[$key])) {
            throw new ObjectNotFoundException('Unknown code');
        }

        return $this->divisions[$key];
    }

    /**
     * @param  string|int  $key
     * @return $this
     */
    public function add($division, $key): static
    {
        $this->divisions[$key] = $division;

        return $this;
    }

    /**
     * Run a filter over each of the items.
     */
    public function filter(?callable $callback = null): static
    {
        if ($callback) {
            return new self($this->manager, array_filter($this->divisions, $callback));
        }

        return new self($this->manager, array_filter($this->divisions));
    }

    public function find(array $params = []): self
    {
        $members = new self($this->manager);

        foreach ($this->divisions as $key => $member) {
            if ($this->match($member, $params)) {
                $members->add($member, $key);
            }
        }

        return $members;
    }

    /**
     * @return Divisible|bool
     */
    public function findOne(array $params = [])
    {
        if (array_keys($params) === ['code']) {
            return $this->get(strtoupper($params['code']));
        }

        return $this->find($params)->first();
    }

    /**
     * Sort the collection
     *
     * @param  string  $field
     * @param  int  $options
     * @param  bool  $descending
     */
    public function sortBy($field, $options = SORT_REGULAR, $descending = false): static
    {
        $results = [];

        foreach ($this->divisions as $key => $value) {
            $meta = $value->toArray();
            $results[$key] = $meta[$field];
        }

        $descending ? arsort($results, $options)
                    : asort($results, $options);

        foreach (array_keys($results) as $key) {
            $results[$key] = $this->divisions[$key];
        }

        return new self($this->manager, $results);
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  int  $offset
     * @param  int  $length
     */
    public function slice($offset, $length = null): static
    {
        return new self($this->manager, array_slice($this->divisions, $offset, $length, true));
    }

    /**
     * Merge collections.
     *
     * @param  array  $divisions
     * @return static
     */
    public function merge($divisions)
    {
        return ($divisions instanceof MemberCollection)
        ? $divisions->merge($this->divisions)
        : new self($this->manager, array_merge($this->divisions, $divisions));
    }

    /**
     * @return bool
     */
    private function match(Divisible $member, array $params)
    {
        $memberArray = $member->toArray();
        $match = true;

        foreach ($params as $key => $value) {
            if (! isset($memberArray[$key]) || strcasecmp($memberArray[$key], $value) !== 0) {
                $match = false;
            }
        }

        return $match;
    }
}
