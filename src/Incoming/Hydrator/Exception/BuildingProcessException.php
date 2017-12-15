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

namespace Incoming\Hydrator\Exception;

use RuntimeException;

/**
 * An exception to be thrown when a building process fails.
 */
class BuildingProcessException extends RuntimeException
{

    /**
     * Constants
     */

    /**
     * The default exception message.
     *
     * @var string
     */
    const DEFAULT_MESSAGE = 'Error occurred during building';


    /**
     * Properties
     */

    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = self::DEFAULT_MESSAGE;
}
