<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Hydrator;

use Incoming\Structure\Map;

/**
 * HydratorInterface
 */
interface HydratorInterface
{

    /**
     * Hydrate a model from the incoming data
     *
     * @param Map $incoming
     * @param mixed $model
     * @return mixed
     */
    public function hydrate(Map $incoming, $model);
}
