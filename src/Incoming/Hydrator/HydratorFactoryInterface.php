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
 *
 * Defines an interface for factory implementations that build a `Hydrator` from
 * a given data model
 *
 * Most implementations will probably use the given model's type or structure to
 * provide and build a hydrator responsible for hydrating that model
 */
interface HydratorFactoryInterface
{

    /**
     * Build a Hydrator for a given data model
     *
     * @param mixed $model The model to hydrate
     * @return HydratorInterface A hydrator capable of hydrating the given model
     */
    public function buildForModel($model);
}
