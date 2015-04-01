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
 * TODO: Document and explain the reasoning for this class to exist
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
     * @return callable The delegate hydrator callable
     */
    protected function getDelegate()
    {
        $delegate = [$this, static::DEFAULT_DELEGATE_METHOD_NAME];

        if (!is_callable($delegate, false, $callable_name)) {
            // TODO: Throw exception
        }

        return $delegate;
    }
}
