<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming;

use Incoming\HydratorInterface;

/**
 * ProcessorInterface
 */
interface ProcessorInterface
{

    /**
     * Process our incoming input into a hydrated model
     *
     * @param mixed $input_data
     * @param mixed $model
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function process($input_data, $model, HydratorInterface $hydrator);
}
