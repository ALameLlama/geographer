<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Collections\Traits;

use ArrayIterator;
use Iterator;

use function array_key_exists;
use function count;

/**
 * Class ImplementsArray
 */
trait ImplementsArray
{
    /**
     * @return ArrayIterator
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->divisions);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->divisions);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->divisions[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->divisions[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->divisions[$offset]);
    }

    public function count(): int
    {
        return count($this->divisions);
    }

    public function serialize(): string
    {
        return serialize($this->divisions);
    }

    public function unserialize(string $serialized): void
    {
        $this->divisions = unserialize($serialized);
    }
}
