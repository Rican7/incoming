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

use Incoming\Structure\Map;

/**
 * An abstract, context enabled extension of the `AbstractDelegateBuilderHydrator`.
 *
 * @see AbstractDelegateBuilderHydrator
 * @see AbstractDelegateContextualBuilder
 * @see AbstractDelegateContextualHydrator
 */
abstract class AbstractDelegateContextualBuilderHydrator extends AbstractDelegateBuilderHydrator implements
    ContextualBuilder,
    ContextualHydrator
{

    /**
     * Properties
     */

    /**
     * Whether or not to provide a fallback empty context, when a `null` context
     * is otherwise provided, to make processes simpler by not having to rely on
     * null checks of the actual parameter before usage.
     *
     * @var bool
     */
    private $provide_fallback_context = false;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param bool $provide_fallback_context Whether or not to provide a
     *  fallback empty context, when a `null` context is otherwise provided, to
     *  make processes simpler by not having to rely on null checks of the
     *  actual parameter before usage.
     */
    protected function __construct(bool $provide_fallback_context = false)
    {
        $this->provide_fallback_context = $provide_fallback_context;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $incoming The input data.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the build process.
     * @return mixed The built model.
     */
    public function build($incoming, Map $context = null)
    {
        $callable = $this->getDelegateBuilder();

        if (null === $context && $this->provide_fallback_context) {
            // Provide a non-null context so null checks aren't later required
            $context = new Map();
        }

        return $callable($incoming, $context);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $incoming The input data.
     * @param mixed $model The model to hydrate.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the hydrate process.
     * @return mixed The hydrated model.
     */
    public function hydrate($incoming, $model, Map $context = null)
    {
        $callable = $this->getDelegateHydrator();

        if (null === $context && $this->provide_fallback_context) {
            // Provide a non-null context so null checks aren't later required
            $context = new Map();
        }

        return $callable($incoming, $model, $context);
    }
}
