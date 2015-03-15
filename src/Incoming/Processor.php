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
use Incoming\Hydrator\HydratorFactoryInterface;
use Incoming\Transformer\PassthruTransformer;
use Incoming\Transformer\TransformerInterface;

/**
 * Processor
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
     * @param TransformerInterface $input_transformer
     */
    public function __construct(TransformerInterface $input_transformer = null)
    {
        $this->input_transformer = $input_transformer ?: new StructureBuilderTransformer();
    }

    /**
     * Get the input transformer
     *
     * @return TransformerInterface
     */
    public function getInputTransformer()
    {
        return $this->input_transformer;
    }

    /**
     * Set the input transformer
     *
     * @param TransformerInterface $input_transformer
     * @return Processor
     */
    public function setInputTransformer(TransformerInterface $input_transformer)
    {
        $this->input_transformer = $input_transformer;

        return $this;
    }

    /**
     * Get the hydrator factory
     *
     * @return HydratorFactoryInterface
     */
    public function getHydratorFactory()
    {
        return $this->hydrator_factory;
    }

    /**
     * Set the hydrator factory
     *
     * @param HydratorFactoryInterface $hydrator_factory
     * @return Processor
     */
    public function setHydratorFactory(HydratorFactoryInterface $hydrator_factory = null)
    {
        $this->hydrator_factory = $hydrator_factory;

        return $this;
    }

    /**
     * Process our incoming input into a hydrated model
     *
     * @param mixed $input_data
     * @param mixed $model
     * @param HydratorInterface $hydrator
     * @return mixed
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
     * @param mixed $input_data
     * @return mixed
     */
    protected function transformInput($input_data)
    {
        return $this->input_transformer->transform($input_data);
    }

    /**
     * Get a Hydrator for a given model
     *
     * @param mixed $model
     * @return HydratorInterface
     */
    protected function getHydratorForModel($model)
    {
        if (null === $this->hydrator_factory) {
            // TODO: Throw an exception?
        }

        return $this->hydrator_factory->buildForModel($model);
    }
}
