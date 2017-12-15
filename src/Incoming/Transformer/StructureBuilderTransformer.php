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

use Incoming\Structure\RecursiveInputStructureFactory;
use Incoming\Structure\Structure;
use Incoming\Structure\StructureFactory;

/**
 * A transformer that takes an input data and returns a Structure
 * representation of the same data
 *
 * Allows for turning loose input into a well-structured container for easier,
 * less error-prone, and more user-friendly data handling
 */
class StructureBuilderTransformer implements Transformer
{

    /**
     * Properties
     */

    /**
     * The factory used to build the Structure instances
     *
     * @var StructureFactory
     */
    private $structure_factory;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param StructureFactory|null $structure_factory The structure factory.
     */
    public function __construct(StructureFactory $structure_factory = null)
    {
        $this->structure_factory = $structure_factory ?: new RecursiveInputStructureFactory();
    }

    /**
     * Get the structure factory
     *
     * @return StructureFactory The structure factory.
     */
    public function getStructureFactory(): StructureFactory
    {
        return $this->structure_factory;
    }

    /**
     * Set the structure factory
     *
     * @param StructureFactory $structure_factory The structure factory.
     * @return $this This instance.
     */
    public function setStructureFactory(StructureFactory $structure_factory): self
    {
        $this->structure_factory = $structure_factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $input The data to transform.
     * @return Structure The transformed data.
     */
    public function transform($input): Structure
    {
        return $this->structure_factory->build($input);
    }
}
