<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Structure;

use ArrayIterator;
use ArrayObject;
use Incoming\Structure\Exception\ReadOnlyException;
use Incoming\Structure\Iterator\ReadOnlyIterator;
use InvalidArgumentException;
use Iterator;
use Traversable;

/**
 * Map
 */
class Map implements StructureInterface
{

    /**
     * Properties
     */

    /**
     * The underlying decorated data structure
     *
     * @type ArrayObject
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
     * Create from data in a Traversable instance
     *
     * @param Traversable $data
     * @return Map
     */
    public static function fromTraversable(Traversable $data)
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
     * Create from data in an array
     *
     * @param array $data
     * @return Map
     */
    public static function fromArray(array $data)
    {
        return static::fromTraversable(
            new ArrayIterator($data)
        );
    }

    /**
     * Check if a given key exists in the map
     *
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Get a value in the map by key
     *
     * @param string $key
     * @param mixed $default_val The default value to return if the key does not exist
     * @return mixed
     */
    public function get($key, $default_val = null)
    {
        if ($this->exists($key)) {
            return $this->offsetGet($key);
        }

        return $default_val;
    }

    /**
     * Check if the map is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }

    /**
     * Get an array list of all of the map's keys
     *
     * @return array
     */
    public function keys()
    {
        return array_keys(
            $this->toArray()
        );
    }

    /**
     * Get a representation of the map as an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->decorated->getArrayCopy();
    }

    /**
     * Get the number of entries in the map
     *
     * @return int
     */
    public function count()
    {
        return count($this->decorated);
    }

    /**
     * Get an iterator instance over the underlying data
     *
     * @return Iterator
     */
    public function getIterator()
    {
        return new ReadOnlyIterator(
            $this->decorated->getIterator()
        );
    }

    /**
     * Check whether an offset exists
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->decorated->offsetExists($offset);
    }

    /**
     * Get the value at the given offset
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->decorated->offsetGet($offset);
    }

    /**
     * Set a value at the given offset
     *
     * @internal
     * @param mixed $offset
     * @param mixed $value
     * @throws ReadOnlyException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException();
    }

    /**
     * Remove the item at the given offset
     *
     * @internal
     * @param mixed $offset
     * @throws ReadOnlyException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException();
    }

    /**
     * Magic "__isset" method
     *
     * Allows access to the map's values via object property/field syntax
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->exists($key);
    }

    /**
     * Magic "__get" method
     *
     * Allows access to the map's values via object property/field syntax
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic "__set" method
     *
     * @internal
     * @param string $key
     * @param mixed $value
     * @throws ReadOnlyException
     * @return void
     */
    public function __set($key, $value)
    {
        throw new ReadOnlyException();
    }

    /**
     * Magic "__unset" method
     *
     * @internal
     * @param string $key
     * @throws ReadOnlyException
     * @return void
     */
    public function __unset($key)
    {
        throw new ReadOnlyException();
    }
}
