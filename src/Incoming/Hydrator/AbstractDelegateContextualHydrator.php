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
 * An abstract, context enabled extension of the `AbstractDelegateHydrator`.
 *
 * @see AbstractDelegateHydrator
 */
abstract class AbstractDelegateContextualHydrator extends AbstractDelegateHydrator implements ContextualHydrator
{

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
        $callable = $this->getDelegate();

        return $callable($incoming, $model, $context);
    }
}
