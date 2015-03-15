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
 */
class StructureFactory implements StructureFactoryInterface
{

    /**
     * Build a structure from a loose-type
     *
     * @param mixed $data
     * @return StructureInterface
     */
    public function build($data)
    {
        return static::buildFromMixed($data);
    }

    /**
     * Build a structure from a mixed-type
     *
     * @param Traversable|array $data
     * @throws InvalidStructuralTypeException If the data type isn't supported
     * @return StructureInterface
     */
    protected static function buildFromMixed($data)
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
     * @param array $data
     * @return StructureInterface
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
     * @param Traversable $data
     * @return StructureInterface
     */
    protected static function buildFromTraversable(Traversable $data)
    {
        $is_map = false;

        // Traverse through the data, but only check the first item's key
        foreach ($data as $key => &$val) {
            $is_map = !is_int($key);

            if (is_array($val)) {
                $val = static::buildFromArray($val);
            } elseif ($val instanceof Traversable) {
                $val = static::buildFromTraversable($val);
            }
        }

        if ($is_map) {
            return Map::fromTraversable($data);
        }

        return FixedList::fromTraversable($data);
    }
}
