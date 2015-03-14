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

use Incoming\Structure\StructureFactory;
use Incoming\Structure\StructureInterface;

/**
 * StructureBuilderTransformer
 *
 * A transformer that takes an input data and returns a StructureInterface
 * representation of the same data
 *
 * Allows for turning loose input into a well-structured container for easier,
 * less error-prone, and more user-friendly data handling
 */
class StructureBuilderTransformer implements TransformerInterface
{

    /**
     * {@inheritdoc}
     *
     * @param mixed $input
     * @return StructureInterface
     */
    public function transform($input)
    {
        // TODO: Allow for a factory instance to be injected?
        return StructureFactory::build($input);
    }
}
