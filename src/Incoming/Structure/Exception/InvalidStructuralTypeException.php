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
 *
 * An exception to be thrown when an invalid type is given to a factory or
 * receiver that expects a type that is convertible or usable as structured data
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
    const MESSAGE_EXTENSION_TYPE_FORMAT = ' `%s`';


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
     * Create an exception instance with type information
     *
     * The type is automatically inspected based on the passed value
     *
     * @param mixed $value The value to inspect type information of
     * @param int $code The exception code
     * @param Exception|null $previous A previous exception used for chaining
     * @return InvalidStructuralTypeException The newly created exception
     */
    public static function withTypeInfo($value, $code = 0, Exception $previous = null)
    {
        $message = self::DEFAULT_MESSAGE;

        $message .= sprintf(
            self::MESSAGE_EXTENSION_TYPE_FORMAT,
            is_object($value) ? get_class($value) : gettype($value)
        );

        return new static($message, $code, $previous);
    }
}
