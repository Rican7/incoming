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

namespace Incoming\Structure;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * A compound interface defining the external interface of a structure type,
 * which is the union of multiple native data-structure interfaces.
 */
interface Structure extends ArrayAccess, Countable, IteratorAggregate
{
}
