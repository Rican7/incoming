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

namespace Incoming\Hydrator;

/**
 * Defines an interface for "building" a well-defined, consistent data model
 * from a loose input structure
 *
 * Builders are basically hydrators, but for models that aren't yet instantiated
 */
interface BuilderInterface
{

    /**
     * Build a model from the incoming data
     *
     * @param mixed $incoming The input data
     * @return mixed The built model
     */
    public function build($incoming);
}
