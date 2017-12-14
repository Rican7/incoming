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

namespace Incoming;

use Incoming\Hydrator\HydratorInterface;

/**
 * Defines an interface for processing loose input data into a hydrated model,
 * using a given hydrator
 */
interface ModelProcessor
{

    /**
     * Process our incoming input into a hydrated model
     *
     * @param mixed $input_data The input data
     * @param mixed $model The model to hydrate
     * @param HydratorInterface $hydrator The hydrator to use in the process
     * @return mixed The hydrated model
     */
    public function processForModel($input_data, $model, HydratorInterface $hydrator);
}
