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

/**
 * HydratorInterface
 */
interface HydratorInterface
{

    /**
     * Hydrate a model from the incoming data
     *
     * @param mixed $incoming
     * @param mixed $model
     * @return mixed
     */
    public function hydrate($incoming, $model);
}
