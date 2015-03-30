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

/**
 * StructureFactoryInterface
 *
 * Defines an interface for building structural data types from a loose input
 */
interface StructureFactoryInterface
{

    /**
     * Build a structure from a loose-type
     *
     * @param mixed $data The input data
     * @return StructureInterface The resulting data-structure
     */
    public function build($data);
}
