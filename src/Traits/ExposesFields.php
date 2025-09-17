<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Traits;

use ALameLlama\Geographer\Exceptions\UnknownFieldException;

use function array_key_exists;
use function call_user_func;
use function count;
use function in_array;
use function is_string;

/**
 * Class ExposedFields
 */
trait ExposesFields
{
    /**
     * @return string|int
     *
     * @throws UnknownFieldException
     */
    public function __call(string $methodName, $args)
    {
        if (preg_match('~^(get)([A-Z])(.*)$~', $methodName, $matches)) {
            $field = strtolower($matches[2]) . $matches[3];

            return $this->__get($field);
        }

        if (preg_match('~^(findOneBy)([A-Z])(.*)$~', $methodName, $matches)) {
            $field = strtolower($matches[2]) . $matches[3];

            return $this->findOne([$field => $args[0]]);
        }

        throw new UnknownFieldException('Method ' . $methodName . ' doesn\'t exist');
    }

    /**
     * @return string
     *
     * @throws UnknownFieldException
     */
    public function __get(string $field)
    {
        if (! array_key_exists($field, $this->exposed) && ! in_array($field, $this->exposed)) {
            throw new UnknownFieldException('Field ' . $field . ' does not exist');
        }

        if (method_exists($this, 'get' . ucfirst($field))) {
            return call_user_func([$this, 'get' . ucfirst($field)]);
        }

        return $this->extract($this->exposed[$field] ?? $field);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->exposed[$offset]);
    }

    /**
     * @throws UnknownFieldException
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (is_string($offset)) {
            return $this->__get($offset);
        }
    }

    /**
     * @param  mixed  $offset  <p>
     * @param  mixed  $value  <p>
     */
    public function offsetSet(mixed $offset, mixed $value): void {}

    public function offsetUnset(mixed $offset): void {}

    public function toArray(): array
    {
        $array = [];

        foreach ($this->exposed as $key => $value) {
            $key = is_numeric($key) ? $value : $key;

            $array[$key] = $this->__get($value);
        }

        return $array;
    }

    /**
     * @param  int  $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param  string  $path
     */
    protected function extract($path): mixed
    {
        $parts = explode('.', $path);

        if (count($parts) === 1) {
            return $this->meta[$path] ?? null;
        }

        $current = &$this->meta;

        foreach ($parts as $field) {
            if (! isset($current[$field])) {
                return null;
            }

            $current = &$current[$field];
        }

        return $current;
    }
}
