<?php

namespace ALameLlama\Geographer\Collections\Traits;

/**
 * Class ImplementsArray
 * @package ALameLlama\Geographer\Collections\Traits
 */
trait ImplementsArray
{
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \Iterator {
        return new \ArrayIterator($this->divisions);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->divisions);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->divisions[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->divisions[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->divisions[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->divisions);
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->divisions);
    }

    /**
     * @param string $serialized
     */
    public function unserialize(string $serialized): void
    {
        $this->divisions = unserialize($serialized);
    }
}
