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

use Incoming\Hydrator\Exception\HydrationProcessException;

/**
 * Defines an interface for "hydrating" a well-defined, consistent data model
 * from a loose input structure
 */
interface HydratorInterface
{

    /**
     * Hydrate a model from the incoming data
     *
     * @param mixed $incoming The input data
     * @param mixed $model The model to hydrate
     * @return mixed The hydrated model
     * @throws HydrationProcessException If an error occurrs during hydration
     */
    public function hydrate($incoming, $model);
}
