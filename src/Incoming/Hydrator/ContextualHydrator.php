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

use Incoming\Hydrator\Exception\HydrationProcessException;
use Incoming\Structure\Map;

/**
 * Defines an interface for hydrators that can accept a "context" parameter,
 * which is a generic key-value map.
 *
 * Context can be useful for passing additional information to the hydrate
 * process, which can enable more advanced conditional processing such as
 * authorization-dependent input handling.
 *
 * The interface is an extension and is compatible with its parent, so that it
 * can be used generically and with the same factories and integrations.
 */
interface ContextualHydrator extends Hydrator
{

    /**
     * Hydrate a model from the incoming data.
     *
     * Accepts an optional context to provide more information and enable more
     * advanced conditional processing.
     *
     * @param mixed $incoming The input data.
     * @param mixed $model The model to hydrate.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the hydrate process.
     * @return mixed The hydrated model.
     * @throws HydrationProcessException If an error occurrs during hydration.
     */
    public function hydrate($incoming, $model, Map $context = null);
}
