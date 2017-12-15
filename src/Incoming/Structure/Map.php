<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

declare(strict_types=1);

namespace Incoming\Structure;

use ArrayIterator;
use ArrayObject;
use Incoming\Structure\Exception\ReadOnlyException;
use Incoming\Structure\Iterator\ReadOnlyIterator;
use InvalidArgumentException;
use Iterator;
use Traversable;

/**
 * A key => value, read-only map data-structure.
 */
class Map implements Structure
{

    /**
     * Properties
     */

    /**
     * The underlying decorated data structure.
     *
     * @var ArrayObject
     */
    private $decorated;


    /**
     * Methods
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->decorated = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Create from data in a Traversable instance.
     *
     * @param Traversable $data The data to create from.
     * @return static The resulting data-structure.
     * @throws InvalidArgumentException If the data contains non-scalar keys.
     */
    public static function fromTraversable(Traversable $data): self
    {
        $map = new static();

        foreach ($data as $key => $val) {
            if (!is_scalar($key)) {
                throw new InvalidArgumentException('Map keys must be scalar');
            }

            $map->decorated->offsetSet($key, $val);
        }

        return $map;
    }

    /**
     * Create from data in an array.
     *
     * @param array $data The data to create from.
     * @return static The resulting data-structure.
     */
    public static function fromArray(array $data): self
    {
        return static::fromTraversable(
            new ArrayIterator($data)
        );
    }

    /**
     * Check if a given key exists in the map.
     *
     * @param mixed $key The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function exists($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Get a value in the map by key.
     *
     * @param mixed $key The key to get the value for.
     * @param mixed $default_val The default value to return if the key does
     *  not exist.
     * @return mixed The resulting value.
     */
    public function get($key, $default_val = null)
    {
        if ($this->exists($key)) {
            return $this->offsetGet($key);
        }

        return $default_val;
    }

    /**
     * Check if the map is empty.
     *
     * @return bool True if the map is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return ($this->count() === 0);
    }

    /**
     * Get an array list of all of the map's keys.
     *
     * @return array The list of the map's keys.
     */
    public function keys(): array
    {
        return array_keys(
            $this->toArray()
        );
    }

    /**
     * Get a representation of the map as an array.
     *
     * @return array The array representation of the map.
     */
    public function toArray(): array
    {
        return $this->decorated->getArrayCopy();
    }

    /**
     * Get the number of entries in the map.
     *
     * @return int The number of entries in the map.
     */
    public function count(): int
    {
        return count($this->decorated);
    }

    /**
     * Get an iterator instance over the underlying data.
     *
     * @return Iterator An iterator scoped to the map's data.
     */
    public function getIterator(): Iterator
    {
        return new ReadOnlyIterator(
            $this->decorated->getIterator()
        );
    }

    /**
     * Check whether an offset exists.
     *
     * @param mixed $offset The offset to check for.
     * @return bool True if the offset exists, false otherwise.
     */
    public function offsetExists($offset): bool
    {
        return $this->decorated->offsetExists($offset);
    }

    /**
     * Get the value at the given offset.
     *
     * @param mixed $offset The offset to get the value for.
     * @return mixed The resulting value.
     */
    public function offsetGet($offset)
    {
        return $this->decorated->offsetGet($offset);
    }

    /**
     * Set a value at the given offset.
     *
     * @internal
     *
     * @param mixed $offset The offset to set the value for.
     * @param mixed $value The value to set.
     * @throws ReadOnlyException External modification is not allowed.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw ReadOnlyException::forAttribute($offset, $value);
    }

    /**
     * Remove the item at the given offset.
     *
     * @internal
     *
     * @param mixed $offset The offset to unset.
     * @throws ReadOnlyException External modification is not allowed.
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw ReadOnlyException::forAttribute($offset);
    }

    /**
     * Magic "__isset" method.
     *
     * Allows access to the map's values via object property/field syntax.
     *
     * @param string $key The key to check for.
     * @return bool True if the key exists, false otherwise.
     */
    public function __isset(string $key): bool
    {
        return $this->exists($key);
    }

    /**
     * Magic "__get" method.
     *
     * Allows access to the map's values via object property/field syntax.
     *
     * @param string $key The key to get the value for.
     * @return mixed The resulting value.
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Magic "__set" method.
     *
     * @internal
     *
     * @param string $key The key to set the value for.
     * @param mixed $value The value to set.
     * @throws ReadOnlyException External modification is not allowed.
     * @return void
     */
    public function __set(string $key, $value)
    {
        throw ReadOnlyException::forAttribute($key, $value);
    }

    /**
     * Magic "__unset" method.
     *
     * @internal
     *
     * @param string $key The key to unset.
     * @throws ReadOnlyException External modification is not allowed.
     * @return void
     */
    public function __unset(string $key)
    {
        throw ReadOnlyException::forAttribute($key);
    }
}
