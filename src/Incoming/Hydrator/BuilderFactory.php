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

/**
 * Defines an interface for factory implementations that build a `Builder` from
 * a given data type
 */
interface BuilderFactory
{

    /**
     * Build a Builder for a given data model
     *
     * @param string $type The type to build
     * @return Builder A builder capable of building the given type
     */
    public function buildForType(string $type): Builder;
}
