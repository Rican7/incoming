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
use Traversable;

/**
 * StructureFactory
 */
class StructureFactory
{

    /**
     * Build a structure from a loose-type
     *
     * @param Traversable|array $data
     * @return StructureInterface
     */
    public static function build($data)
    {
        if (is_array($data)) {
            return static::buildFromArray($data);
        }

        return static::buildFromTraversable($data);
    }

    /**
     * Build a structure from data in an array
     *
     * @param array $data
     * @return StructureInterface
     */
    public static function buildFromArray(array $data)
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
    public static function buildFromTraversable(Traversable $data)
    {
        $is_map = false;

        // Traverse through the data, but only check the first item's key
        foreach ($data as $key => &$val) {
            $is_map = !is_int($key);

            if (is_array($val)) {
                $val = static::buildFromArray($val);
            } elseif ($val instanceof Traversable) {
                $val = static::buildFromArray($val);
            }
        }

        if ($is_map) {
            return Map::fromTraversable($data);
        }

        return FixedList::fromTraversable($data);
    }
}
