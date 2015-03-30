<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Structure\Iterator;

use Iterator;

/**
 * ReadOnlyIterator
 *
 * A very basic iterator implementation that decorates another iterator but only
 * allows the most basic of iterator operations without having access to the
 * underlying iterator
 */
final class ReadOnlyIterator implements Iterator
{

    /**
     * Properties
     */

    /**
     * The underlying decorated iterator
     *
     * @type Iterator
     */
    private $decorated;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param Iterator $decorated The iterator to decorate as read-only
     */
    public function __construct(Iterator $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * Return the current element
     *
     * @return mixed The current element
     */
    public function current()
    {
        return $this->decorated->current();
    }

    /**
     * Return the key of the current element
     *
     * @return scalar The key of the current element
     */
    public function key()
    {
        return $this->decorated->key();
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        $this->decorated->next();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        $this->decorated->rewind();
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean True if the current position is valid, false otherwise
     */
    public function valid()
    {
        return $this->decorated->valid();
    }
}
