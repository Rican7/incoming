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
use Incoming\Structure\Exception\InvalidStructuralTypeException;
use Traversable;

/**
 * StructureFactory
 *
 * A default implementation of the `StructureFactoryInterface` for building
 * the included structural types from loose input data
 */
class StructureFactory implements StructureFactoryInterface
{

    /**
     * Build a structure from a loose-type
     *
     * @param mixed $data The input data
     * @return StructureInterface The resulting data-structure
     */
    public function build($data)
    {
        return static::buildFromTraversableLike($data);
    }

    /**
     * Build a structure from "Traversable-like" data
     *
     * This allows to build from data that is either an array or Traversable,
     * as PHP's array type works JUST like a Traversable instance but doesn't
     * actually implement any interfaces
     *
     * @param Traversable|array $data The input data
     * @throws InvalidStructuralTypeException If the data type isn't supported
     * @return StructureInterface The built structure
     */
    protected static function buildFromTraversableLike($data)
    {
        if (is_array($data)) {
            return static::buildFromArray($data);
        } elseif ($data instanceof Traversable) {
            return static::buildFromTraversable($data);
        }

        throw InvalidStructuralTypeException::withTypeInfo($data);
    }

    /**
     * Build a structure from data in an array
     *
     * @param array $data The input data
     * @return StructureInterface The built structure
     */
    protected static function buildFromArray(array $data)
    {
        return static::buildFromTraversable(
            new ArrayIterator($data)
        );
    }

    /**
     * Build a structure from data in a Traversable instance
     *
     * @param Traversable $data The input data
     * @return StructureInterface The built structure
     */
    protected static function buildFromTraversable(Traversable $data)
    {
        $is_map = false;

        // Traverse through the data, but only check the first item's key
        foreach ($data as $key => &$val) {
            $is_map = $is_map || !is_int($key);

            $val = self::attemptBuildTraversableLike($val);
        }

        if ($is_map) {
            return Map::fromTraversable($data);
        }

        return FixedList::fromTraversable($data);
    }

    /**
     * Attempt to build a structure from "Traversable-like" data
     *
     * If the data type isn't supported, we simply return the original data
     * untouched. This allows to more easily traverse deeply nested structures
     *
     * @param mixed $data The input data
     * @return StructureInterface|mixed The built structure or original data
     */
    private static function attemptBuildTraversableLike($data)
    {
        if (is_array($data) || $data instanceof Traversable) {
            $data = static::buildFromTraversableLike($data);
        }

        return $data;
    }
}
