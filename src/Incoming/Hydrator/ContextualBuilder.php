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

use Incoming\Hydrator\Exception\BuildingProcessException;
use Incoming\Structure\Map;

/**
 * Defines an interface for builders that can accept a "context" parameter,
 * which is a generic key-value map.
 *
 * Context can be useful for passing additional information to the build
 * process, which can enable more advanced conditional processing such as
 * authorization-dependent input handling.
 *
 * The interface is an extension and is compatible with its parent, so that it
 * can be used generically and with the same factories and integrations.
 */
interface ContextualBuilder extends Builder
{

    /**
     * Build a model from the incoming data.
     *
     * Accepts an optional context to provide more information and enable more
     * advanced conditional processing.
     *
     * @param mixed $incoming The input data.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the build process.
     * @return mixed The built model.
     * @throws BuildingProcessException If an error occurrs during building.
     */
    public function build($incoming, Map $context = null);
}
