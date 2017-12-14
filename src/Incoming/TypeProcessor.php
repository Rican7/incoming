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

use Incoming\Hydrator\BuilderInterface;
use Incoming\Hydrator\HydratorInterface;

/**
 * Defines an interface for processing loose input data into a built model,
 * using a given builder
 */
interface TypeProcessor
{

    /**
     * Process our incoming input into a built model
     *
     * @param mixed $input_data The input data
     * @param string $type The type to build
     * @param BuilderInterface $builder The builder to use in the process
     * @param HydratorInterface $hydrator An optional hydrator to use in the
     *  process, after the type is built, to aid in the full hydration of the
     *  resulting model
     * @return mixed The built model
     */
    public function processForType(
        $input_data,
        string $type,
        BuilderInterface $builder,
        HydratorInterface $hydrator = null
    );
}
