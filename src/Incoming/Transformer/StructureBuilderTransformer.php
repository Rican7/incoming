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

namespace Incoming\Transformer;

use Incoming\Structure\StructureFactory;
use Incoming\Structure\StructureFactoryInterface;
use Incoming\Structure\StructureInterface;

/**
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
     * @var StructureFactoryInterface
     */
    private $structure_factory;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param StructureFactoryInterface|null $structure_factory The structure factory
     */
    public function __construct(StructureFactoryInterface $structure_factory = null)
    {
        $this->structure_factory = $structure_factory ?: new StructureFactory();
    }

    /**
     * Get the structure factory
     *
     * @return StructureFactoryInterface The structure factory
     */
    public function getStructureFactory(): StructureFactoryInterface
    {
        return $this->structure_factory;
    }

    /**
     * Set the structure factory
     *
     * @param StructureFactoryInterface $structure_factory The structure factory
     * @return $this This instance
     */
    public function setStructureFactory(StructureFactoryInterface $structure_factory): self
    {
        $this->structure_factory = $structure_factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $input The data to transform
     * @return StructureInterface The transformed data
     */
    public function transform($input): StructureInterface
    {
        return $this->structure_factory->build($input);
    }
}
