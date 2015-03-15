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
 */
interface StructureFactoryInterface
{

    /**
     * Build a structure from a loose-type
     *
     * @param mixed $data
     * @return StructureInterface
     */
    public function build($data);
}
