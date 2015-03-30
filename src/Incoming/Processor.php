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

use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use Incoming\Hydrator\HydratorFactoryInterface;
use Incoming\Hydrator\HydratorInterface;
use Incoming\Transformer\StructureBuilderTransformer;
use Incoming\Transformer\TransformerInterface;

/**
 * Processor
 *
 * A default implementation of the `ProcessorInterface` for processing input
 * data with an optional input transformation phase and automatic hydrator
 * resolution
 */
class Processor implements ProcessorInterface
{

    /**
     * Properties
     */

    /**
     * An input transformer to pre-process the input data before hydration
     *
     * @type TransformerInterface
     */
    private $input_transformer;

    /**
     * A factory for building hydrators for a given model
     *
     * @type HydratorFactoryInterface
     */
    private $hydrator_factory;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param TransformerInterface|null $input_transformer The input transformer
     */
    public function __construct(TransformerInterface $input_transformer = null)
    {
        $this->input_transformer = $input_transformer ?: new StructureBuilderTransformer();
    }

    /**
     * Get the input transformer
     *
     * @return TransformerInterface The input transformer
     */
    public function getInputTransformer()
    {
        return $this->input_transformer;
    }

    /**
     * Set the input transformer
     *
     * @param TransformerInterface $input_transformer The input transformer
     * @return Processor This instance
     */
    public function setInputTransformer(TransformerInterface $input_transformer)
    {
        $this->input_transformer = $input_transformer;

        return $this;
    }

    /**
     * Get the hydrator factory
     *
     * @return HydratorFactoryInterface The hydrator factory
     */
    public function getHydratorFactory()
    {
        return $this->hydrator_factory;
    }

    /**
     * Set the hydrator factory
     *
     * @param HydratorFactoryInterface|null $hydrator_factory The hydrator factory
     * @return Processor This instance
     */
    public function setHydratorFactory(HydratorFactoryInterface $hydrator_factory = null)
    {
        $this->hydrator_factory = $hydrator_factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * If a hydrator isn't provided, an attempt will be made to automatically
     * resolve and build an appropriate hydrator from the provided factory
     *
     * @param mixed $input_data The input data
     * @param mixed $model The model to hydrate
     * * @param HydratorInterface|null $hydrator The hydrator to use
     * @return mixed The hydrated model
     */
    public function process($input_data, $model, HydratorInterface $hydrator = null)
    {
        $input_data = $this->transformInput($input_data);

        if (null === $hydrator) {
            $hydrator = $this->getHydratorForModel($model);
        }

        return $hydrator->hydrate($input_data, $model);
    }

    /**
     * Transform the input data
     *
     * @param mixed $input_data The input data
     * @return mixed The resulting transformed data
     */
    protected function transformInput($input_data)
    {
        return $this->input_transformer->transform($input_data);
    }

    /**
     * Get a Hydrator for a given model
     *
     * @param mixed $model The model to get a hydrator for
     * @throws UnresolvableHydratorException If a hydrator can't be resolved for
     *  the given model
     * @return HydratorInterface The resulting hydrator
     */
    protected function getHydratorForModel($model)
    {
        if (null === $this->hydrator_factory) {
            throw UnresolvableHydratorException::forModel($model);
        }

        return $this->hydrator_factory->buildForModel($model);
    }
}
