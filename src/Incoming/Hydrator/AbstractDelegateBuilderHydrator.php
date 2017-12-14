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

namespace Incoming\Hydrator;

use Incoming\Hydrator\Exception\InvalidDelegateException;

/**
 * An abstract builder and hydrator that allows for the building and hydrating
 * to be delegated to another callable. By default, a named method is attempted
 * to be found, but any callable could be returned through overrides.
 *
 * @see AbstractDelegateBuilder
 * @see AbstractDelegateHydrator
 */
abstract class AbstractDelegateBuilderHydrator implements Builder, Hydrator
{

    /**
     * Constants
     */

    /**
     * The name of the default delegate build method
     *
     * @var string
     */
    const DEFAULT_DELEGATE_BUILD_METHOD_NAME = 'buildModel';

    /**
     * The name of the default delegate hydrate method
     *
     * @var string
     */
    const DEFAULT_DELEGATE_HYDRATE_METHOD_NAME = 'hydrateModel';


    /**
     * Methods
     */

    /**
     * {@inheritdoc}
     *
     * @param mixed $incoming The input data
     * @return mixed The built model
     */
    public function build($incoming)
    {
        return call_user_func(
            $this->getDelegateBuilder(),
            $incoming
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $incoming The input data
     * @param mixed $model The model to hydrate
     * @return mixed The hydrated model
     */
    public function hydrate($incoming, $model)
    {
        return call_user_func(
            $this->getDelegateHydrator(),
            $incoming,
            $model
        );
    }

    /**
     * Get the delegate building callable
     *
     * Override this method if a custom delegate is desired
     *
     * @return callable The delegate builder callable
     */
    protected function getDelegateBuilder(): callable
    {
        $delegate = [$this, static::DEFAULT_DELEGATE_BUILD_METHOD_NAME];

        if (!is_callable($delegate, false, $callable_name)) {
            throw InvalidDelegateException::forNonCallable($callable_name);
        }

        return $delegate;
    }

    /**
     * Get the delegate hydration callable
     *
     * Override this method if a custom delegate is desired
     *
     * @return callable The delegate hydrator callable
     */
    protected function getDelegateHydrator(): callable
    {
        $delegate = [$this, static::DEFAULT_DELEGATE_HYDRATE_METHOD_NAME];

        if (!is_callable($delegate, false, $callable_name)) {
            throw InvalidDelegateException::forNonCallable($callable_name);
        }

        return $delegate;
    }

    /**
     * The delegate build method
     *
     * This doc-block and commented out abstract method is provided here to show
     * what the delegate method signature WOULD be if PHP allowed the proper
     * typing support to enable a generic definition in this manner
     *
     * See the class description for more info
     *
     * @param IncomingDataType $incoming The input data
     * @return ModelType The built model
     */
    // abstract protected function buildModel(IncomingDataType $incoming): ModelType;

    /**
     * The delegate hydrate method
     *
     * This doc-block and commented out abstract method is provided here to show
     * what the delegate method signature WOULD be if PHP allowed the proper
     * typing support to enable a generic definition in this manner
     *
     * See the class description for more info
     *
     * @param IncomingDataType $incoming The input data
     * @param ModelType $model The model to hydrate
     * @return ModelType The hydrated model
     */
    // abstract protected function hydrateModel(IncomingDataType $incoming, ModelType $model): ModelType;
}
