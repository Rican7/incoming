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

use Incoming\Hydrator\Builder;
use Incoming\Hydrator\Hydrator;
use Throwable;
use UnexpectedValueException;

/**
 * An exception to be thrown when a hydrator or builder is incompatible with an
 * expected process strategy, such as when a non-context-compatible hydrator is
 * provided with a non-null context.
 */
class IncompatibleProcessException extends UnexpectedValueException
{

    /**
     * Constants
     */

    /**
     * The default exception message.
     *
     * @var string
     */
    const DEFAULT_MESSAGE = 'Provided or resolved process is not compatible';

    /**
     * The exception code for when context compatibility is required.
     *
     * @var int
     */
    const CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY = 1;

    /**
     * The exception code for when a hydrator is used as the process.
     *
     * @var int
     */
    const CODE_FOR_HYDRATOR = 2;

    /**
     * The exception code for when a builder is used as the process.
     *
     * @var int
     */
    const CODE_FOR_BUILDER = 4;

    /**
     * The message extension (appended to the default message) for when context
     * compatibility is required.
     *
     * @var string
     */
    const MESSAGE_EXTENSION_FOR_REQUIRED_CONTEXT_COMPATIBILITY = ' due to requiring context compatibility';


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
     * Create an exception instance for when context compatibility is required.
     *
     * @param object|null $process The incompatible process.
     * @param int $code The exception code.
     * @param Throwable|null $previous A previous exception used for chaining.
     * @return static The newly created exception.
     */
    public static function forRequiredContextCompatibility(
        $process = null,
        int $code = self::CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY,
        Throwable $previous = null
    ): self {
        if (self::CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY === $code) {
            if ($process instanceof Hydrator) {
                $code += self::CODE_FOR_HYDRATOR;
            } elseif ($process instanceof Builder) {
                $code += self::CODE_FOR_BUILDER;
            }
        }

        $message = self::DEFAULT_MESSAGE . self::MESSAGE_EXTENSION_FOR_REQUIRED_CONTEXT_COMPATIBILITY;

        return new static($message, $code, $previous);
    }
}
