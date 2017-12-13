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

use BadMethodCallException;
use Exception;

/**
 * An exception to be thrown when an attempt is made to modify a read-only
 * value, property, or state
 */
class ReadOnlyException extends BadMethodCallException
{

    /**
     * Constants
     */

    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Illegal modification attempt';

    /**
     * The exception code for an exception with an attribute context
     *
     * @var int
     */
    const CODE_FOR_ATTRIBUTE = 1;

    /**
     * The message extension format for providing an attribute context's info
     *
     * @var string
     */
    const MESSAGE_EXTENSION_FOR_ATTRIBUTE_FORMAT = ' for attribute `%s`';

    /**
     * The message extension format for providing an attribute's value info
     * in addition to the attribute's context
     *
     * @var string
     */
    const MESSAGE_EXTENSION_FOR_ATTRIBUTE_WITH_VALUE_FORMAT = ' and value `%s`';


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
     * Create an exception instance with an attribute's context
     *
     * @param string $name The name of the attribute attempted to be modified
     * @param mixed|null $value The value attempted to be set
     * @param int $code The exception code
     * @param Exception|null $previous A previous exception used for chaining
     * @return static The newly created exception
     */
    public static function forAttribute(
        $name,
        $value = null,
        $code = self::CODE_FOR_ATTRIBUTE,
        Exception $previous = null
    ): self {
        $message = self::DEFAULT_MESSAGE;

        if (null !== $value) {
            $message .= sprintf(
                self::MESSAGE_EXTENSION_FOR_ATTRIBUTE_FORMAT . self::MESSAGE_EXTENSION_FOR_ATTRIBUTE_WITH_VALUE_FORMAT,
                $name,
                var_export($value, true)
            );
        } else {
            $message .= sprintf(
                self::MESSAGE_EXTENSION_FOR_ATTRIBUTE_FORMAT,
                $name
            );
        }

        return new static($message, $code, $previous);
    }
}
