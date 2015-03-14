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
 * HydratorFactoryInterface
 */
interface HydratorFactoryInterface
{

    /**
     * Build a Hydrator for a given data model
     *
     * @param mixed $model
     * @return HydratorInterface
     */
    public function buildForModel($model);
}
