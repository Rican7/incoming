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
 * An abstract, context enabled extension of the `AbstractDelegateBuilder`.
 *
 * @see AbstractDelegateBuilder
 */
abstract class AbstractDelegateContextualBuilder extends AbstractDelegateBuilder implements ContextualBuilder
{

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
        $callable = $this->getDelegate();

        return $callable($incoming, $context);
    }
}
