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

use Throwable;
use UnexpectedValueException;

/**
 * An exception to be thrown when a builder can't be resolved, whether by
 * automatic lookup or not, and usually for a given type
 */
class UnresolvableBuilderException extends UnexpectedValueException
{

    /**
     * Constants
     */

    /**
     * @var string
     */
    const DEFAULT_MESSAGE = 'Unable to resolve a builder';

    /**
     * The exception code for when a type is used as the resolver argument
     *
     * @var int
     */
    const CODE_FOR_TYPE = 1;

    /**
     * The message extension (appended to the default message) for when a type
     * is used as the builder resolver argument
     *
     * @var string
     */
    const MESSAGE_EXTENSION_FOR_TYPE = ' for the given type `%s`';


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
     * Create an exception instance for a problem resolving a builder for a
     * given type
     *
     * @param string $type The type to build
     * @param int $code The exception code
     * @param Throwable|null $previous A previous exception used for chaining
     * @return static The newly created exception
     */
    public static function forType(
        string $type,
        int $code = self::CODE_FOR_TYPE,
        Throwable $previous = null
    ): self {
        $message = self::DEFAULT_MESSAGE . sprintf(self::MESSAGE_EXTENSION_FOR_TYPE, $type);

        return new static($message, $code, $previous);
    }
}
