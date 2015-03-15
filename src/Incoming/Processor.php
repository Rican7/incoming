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

        // TODO: Get a hydrator if not passed. Hydrate the model. The return the hydrated model.
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
}
