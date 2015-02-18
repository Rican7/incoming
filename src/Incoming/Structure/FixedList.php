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

use ArrayAccess;
use Countable;
use Incoming\Structure\Exception\ReadOnlyException;
use Incoming\Structure\Iterator\ReadOnlyIterator;
use Iterator;
use IteratorAggregate;
use SplFixedArray;
use Traversable;

/**
 * FixedList
 */
class FixedList implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     * Properties
     */

    /**
     * The underlying decorated data structure
     *
     * @type SplFixedArray
     */
    private $decorated;


    /**
     * Methods
     */

    /**
     * Constructor
     */
    public function __construct($size = 0)
    {
        $this->decorated = new SplFixedArray($size);
    }

    /**
     * Create from data in an array
     *
     * @param array $data
     * @return FixedList
     */
    public static function fromArray(array $data)
    {
        $fixed_list = new static();

        $fixed_list->decorated = SplFixedArray::fromArray($data, false);

        return $fixed_list;
    }

    /**
     * Create from data in a Traversable instance
     *
     * @param Traversable $data
     * @return FixedList
     */
    public static function fromTraversable(Traversable $data)
    {
        return static::fromArray(
            iterator_to_array($data)
        );
    }

    /**
     * Get a value in the list by index
     *
     * @param int $index
     * @param mixed $default_val The default value to return if the index does not exist
     * @return mixed
     */
    public function get($index, $default_val = null)
    {
        if ($this->offsetExists($index)) {
            return $this->offsetGet($index);
        }

        return $default_val;
    }

    /**
     * Check if the list is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }

    /**
     * Get a representation of the list as an array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->decorated->toArray();
    }

    /**
     * Get the number of entries in the list
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
            $this->decorated
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
}
