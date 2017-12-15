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

use BadFunctionCallException;
use Throwable;

/**
 * An exception to be thrown when an invalid delegate method, function, or
 * callback is provided to a caller
 */
class InvalidDelegateException extends BadFunctionCallException
{

    /**
     * Constants
     */

    /**
     * The default exception message.
     *
     * @var string
     */
    const DEFAULT_MESSAGE = 'Invalid delegate';

    /**
     * The exception code for when a delegate isn't callable
     *
     * @var int
     */
    const CODE_FOR_NON_CALLABLE = 1;

    /**
     * The message extension for when a delegate isn't callable
     *
     * @var string
     */
    const MESSAGE_EXTENSION_FOR_NON_CALLABLE = ' is unable to be called';

    /**
     * The message extension format for when a delegate's name is provided
     *
     * @var string
     */
    const MESSAGE_EXTENSION_NAME_FORMAT = ' named `%s`';


    /**
     * Properties
     */

    /**
     * The exception message.
     *
     * @var string
     */
    protected $message = self::DEFAULT_MESSAGE;


    /**
     * Methods
     */

    /**
     * Create an exception instance for a delegate that isn't callable
     *
     * @param string|null $name The name of the delegate.
     * @param int $code The exception code.
     * @param Throwable|null $previous A previous exception used for chaining.
     * @return static The newly created exception.
     */
    public static function forNonCallable(
        string $name = null,
        int $code = self::CODE_FOR_NON_CALLABLE,
        Throwable $previous = null
    ): self {
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
