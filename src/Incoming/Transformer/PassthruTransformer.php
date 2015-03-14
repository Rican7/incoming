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
 * PassthruTransformer
 *
 * A transformer that simply returns the input data as is
 *
 * Great for use as part of the "Null Object" pattern as its essentially a no-op
 */
class PassthruTransformer implements TransformerInterface
{

    /**
     * {@inheritdoc}
     *
     * @param mixed $input
     * @return mixed
     */
    public function transform($input)
    {
        return $input;
    }
}
