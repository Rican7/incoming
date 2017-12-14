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
 * An abstract builder that allows for the building to be delegated to another
 * callable. By default, a named method is attempted to be found, but any
 * callable could be returned through overrides.
 *
 * This enables a lot of interesting uses, most notably this allows builders to
 * be created that have strongly type-hinted building arguments while still
 * perfectly satisfying the `Builder`. Essentially this allows the bypassing of
 * the type variance rules enforced by PHP in a way that provides a
 * generics-like definition. Ultimately, if/when PHP gets generics this will no
 * longer be necessary, as one could simply implement a builder using typed
 * arguments like: `Builder<IncomingDataType, ModelType>`
 *
 * @link http://en.wikipedia.org/wiki/Covariance_and_contravariance_(computer_science)
 * @link http://en.wikipedia.org/wiki/Generic_programming
 */
abstract class AbstractDelegateBuilder implements Builder
{

    /**
     * Constants
     */

    /**
     * The name of the default delegate method
     *
     * @var string
     */
    const DEFAULT_DELEGATE_METHOD_NAME = 'buildModel';


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
            $this->getDelegate(),
            $incoming
        );
    }

    /**
     * Get the delegate building callable
     *
     * Override this method if a custom delegate is desired
     *
     * @return callable The delegate builder callable
     */
    protected function getDelegate(): callable
    {
        $delegate = [$this, static::DEFAULT_DELEGATE_METHOD_NAME];

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
}
