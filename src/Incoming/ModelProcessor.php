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

use Incoming\Hydrator\Hydrator;
use Incoming\Structure\Map;

/**
 * Defines an interface for processing loose input data into a hydrated model,
 * using a given hydrator.
 */
interface ModelProcessor
{

    /**
     * Process our incoming input into a hydrated model.
     *
     * @param mixed $input_data The input data.
     * @param mixed $model The model to hydrate.
     * @param Hydrator $hydrator The hydrator to use in the process.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the process.
     * @return mixed The hydrated model.
     */
    public function processForModel($input_data, $model, Hydrator $hydrator, Map $context = null);
}
