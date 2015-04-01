<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Hydrator\Exception;

use BadFunctionCallException;
use Exception;

/**
 * InvalidDelegateException
 *
 * An exception to be thrown when an invalid delegate method, function, or
 * callback is provided to a caller
 */
class InvalidDelegateException extends BadFunctionCallException
{

    /**
     * Constants
     */

    /**
     * @type string
     */
    const DEFAULT_MESSAGE = 'Invalid delegate';

    /**
     * The exception code for when a delegate isn't callable
     *
     * @type int
     */
    const CODE_FOR_NON_CALLABLE = 1;

    /**
     * The message extension for when a delegate isn't callable
     *
     * @type string
     */
    const MESSAGE_EXTENSION_FOR_NON_CALLABLE = ' is unable to be called';

    /**
     * The message extension format for when a delegate's name is provided
     *
     * @type string
     */
    const MESSAGE_EXTENSION_NAME_FORMAT = ' named `%s`';


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
     * Create an exception instance for a delegate that isn't callable
     *
     * @param mixed|null $name The name of the delegate
     * @param int $code The exception code
     * @param Exception|null $previous A previous exception used for chaining
     * @return InvalidDelegateException The newly created exception
     */
    public static function forNonCallable($name = null, $code = self::CODE_FOR_NON_CALLABLE, Exception $previous = null)
    {
        $message = self::DEFAULT_MESSAGE;

        if (null !== $name) {
            $message .= sprintf(
                self::MESSAGE_EXTENSION_NAME_FORMAT,
                $name
            );
        }

        $message .= self::MESSAGE_EXTENSION_FOR_NON_CALLABLE;

        return new static($message, $code, $previous);
    }
}
