<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Transformer;

/**
 * TransformerInterface
 *
 * Defines an interface used for transforming input data into another state or
 * type
 *
 * Mostly useful for transforming a raw input array into a proper data
 * structure, but also useful for cleaning up or sanitizing input data in a
 * generic and reusable fashion
 */
interface TransformerInterface
{

    /**
     * Transform input data to another output state/type
     *
     * @param mixed $input The data to transform
     * @return mixed The transformed data
     */
    public function transform($input);
}
