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

namespace Incoming\Structure\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * An exception to be thrown when an invalid type is given to a factory or
 * receiver that expects a type that is convertible or usable as structured data
 */
class InvalidStructuralTypeException extends InvalidArgumentException
{

    /**
     * Constants
     */

    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Invalid structural type';

    /**
     * The message extension format for providing type information
     *
     * @var string
     */
    const MESSAGE_EXTENSION_TYPE_FORMAT = ' `%s`';


    /**
     * Properties
     */

    /**
     * @var string
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
     * @param Throwable|null $previous A previous exception used for chaining
     * @return static The newly created exception
     */
    public static function withTypeInfo($value, int $code = 0, Throwable $previous = null): self
    {
        $message = self::DEFAULT_MESSAGE;

        $message .= sprintf(
            self::MESSAGE_EXTENSION_TYPE_FORMAT,
            is_object($value) ? get_class($value) : gettype($value)
        );

        return new static($message, $code, $previous);
    }
}
