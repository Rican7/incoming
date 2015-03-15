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
use Incoming\Structure\StructureFactoryInterface;
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
     * Properties
     */

    /**
     * The factory used to build the StructureInterface instances
     *
     * @type StructureFactoryInterface
     */
    private $structure_factory;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param StructureFactoryInterface|null $structure_factory
     */
    public function __construct(StructureFactoryInterface $structure_factory = null)
    {
        $this->structure_factory = $structure_factory ?: new StructureFactory();
    }

    /**
     * Get the structure factory
     *
     * @return StructureFactoryInterface
     */
    public function getStructureFactory()
    {
        return $this->structure_factory;
    }

    /**
     * Set the structure factory
     *
     * @param StructureFactoryInterface $structure_factory
     * @return StructureBuilderTransformer
     */
    public function setStructureFactory(StructureFactoryInterface $structure_factory)
    {
        $this->structure_factory = $structure_factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $input
     * @return StructureInterface
     */
    public function transform($input)
    {
        return $this->structure_factory->build($input);
    }
}
