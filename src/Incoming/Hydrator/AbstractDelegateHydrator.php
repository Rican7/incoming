<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Hydrator;

use Incoming\Hydrator\Exception\InvalidDelegateException;

/**
 * AbstractDelegateHydrator
 *
 * An abstract hydrator that allows for the hydration to be delegated to another
 * callable. By default, a named method is attempted to be found, but any
 * callable could be returned through overrides.
 *
 * This enables a lot of interesting uses, most notably this allows hydrators to
 * be created that have strongly type-hinted hydration arguments while still
 * perfectly satisfying the `HydratorInterface`. Essentially this allows the
 * bypassing of the type variance rules enforced by PHP in a way that provides a
 * generics-like definition. Ultimately, if/when PHP gets generics this will no
 * longer be necessary, as one could simply implement a hydrator using typed
 * arguments like: `HydratorInterface<IncomingDataType, ModelType>`
 *
 * @link http://en.wikipedia.org/wiki/Covariance_and_contravariance_(computer_science)
 * @link http://en.wikipedia.org/wiki/Generic_programming
 */
abstract class AbstractDelegateHydrator implements HydratorInterface
{

    /**
     * Constants
     */

    /**
     * The name of the default delegate method
     *
     * @type string
     */
    const DEFAULT_DELEGATE_METHOD_NAME = 'hydrateModel';


    /**
     * Methods
     */

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
            $this->getDelegate(),
            $incoming,
            $model
        );
    }

    /**
     * Get the delegate hydration callable
     *
     * Override this method if a custom delegate is desired
     *
     * @return callable The delegate hydrator callable
     */
    protected function getDelegate()
    {
        $delegate = [$this, static::DEFAULT_DELEGATE_METHOD_NAME];

        if (!is_callable($delegate, false, $callable_name)) {
            throw InvalidDelegateException::forNonCallable($callable_name);
        }

        return $delegate;
    }

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
    // abstract protected function hydrateModel(IncomingDataType $incoming, ModelType $model);
}
