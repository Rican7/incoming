<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Structure\Exception;

use Exception;
use InvalidArgumentException;

/**
 * InvalidStructuralTypeException
 */
class InvalidStructuralTypeException extends InvalidArgumentException
{

    /**
     * Constants
     */

    /**
     * @type string
     */
    const DEFAULT_MESSAGE = 'Invalid structural type';

    /**
     * The message extension format for providing type information
     *
     * @type string
     */
    const MESSAGE_EXTENSION_TYPE_FORMAT = ' of type `%s`';


    /**
     * Properties
     */

    /**
     * @type string
     */
    protected $message = self::DEFAULT_MESSAGE;


    /**
     * Methods
     */

    /**
     * Create an exception instance with a value's context
     *
     * @param mixed $value
     * @param int $code
     * @param Exception|null $previous
     * @return InvalidStructuralTypeException
     */
    public static function forValue($value, $code = 0, Exception $previous = null)
    {
        $message = self::DEFAULT_MESSAGE . self::MESSAGE_EXTENSION_TYPE_FORMAT;

        $type = is_object($value) ? get_class($value) : gettype($value);

        $message = sprintf(
            $message,
            $type
        );

        return new static($message, $code, $previous);
    }
}
