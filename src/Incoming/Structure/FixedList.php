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

use Incoming\Structure\Exception\ReadOnlyException;
use Incoming\Structure\Iterator\ReadOnlyIterator;
use Iterator;
use SplFixedArray;
use Traversable;

/**
 * A fixed-size, read-only data-structure.
 */
class FixedList implements Structure
{

    /**
     * Properties
     */

    /**
     * The underlying decorated data structure.
     *
     * @var SplFixedArray
     */
    private $decorated;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param int $size The size (length) of the list.
     */
    public function __construct(int $size = 0)
    {
        $this->decorated = new SplFixedArray($size);
    }

    /**
     * Create from data in an array.
     *
     * @param array $data The data to create from.
     * @return static The resulting data-structure.
     */
    public static function fromArray(array $data): self
    {
        $fixed_list = new static();

        $fixed_list->decorated = SplFixedArray::fromArray($data, false);

        return $fixed_list;
    }

    /**
     * Create from data in a Traversable instance.
     *
     * @param Traversable $data The data to create from.
     * @return static The resulting data-structure.
     */
    public static function fromTraversable(Traversable $data): self
    {
        return static::fromArray(
            iterator_to_array($data)
        );
    }

    /**
     * Check if a given index exists in the list.
     *
     * @param int $index The index to check for existence.
     * @return bool True if the index exists, false otherwise.
     */
    public function exists(int $index): bool
    {
        return $this->offsetExists($index);
    }

    /**
     * Get a value in the list by index.
     *
     * @param int $index The index to get the value for.
     * @param mixed $default_val The default value to return if the index does
     *  not exist.
     * @return mixed The resulting value.
     */
    public function get(int $index, $default_val = null)
    {
        if ($this->offsetExists($index)) {
            return $this->offsetGet($index);
        }

        return $default_val;
    }

    /**
     * Check if the list is empty.
     *
     * @return bool True if the list is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return ($this->count() === 0);
    }

    /**
     * Get a representation of the list as an array.
     *
     * @return array The array representation of the list.
     */
    public function toArray(): array
    {
        return $this->decorated->toArray();
    }

    /**
     * Get the number of entries in the list.
     *
     * @return int The number of entries in the list.
     */
    public function count(): int
    {
        return count($this->decorated);
    }

    /**
     * Get an iterator instance over the underlying data.
     *
     * @return Iterator An iterator scoped to the list's data.
     */
    public function getIterator(): Iterator
    {
        return new ReadOnlyIterator(
            $this->decorated
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
}
