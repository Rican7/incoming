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

use Exception;
use UnexpectedValueException;

/**
 * UnresolvableHydratorException
 */
class UnresolvableHydratorException extends UnexpectedValueException
{

    /**
     * Constants
     */

    /**
     * @type string
     */
    const DEFAULT_MESSAGE = 'Unable to resolve a hydrator';

    /**
     * The message extension (appended to the default message) for when a model
     * is used as the hydrator resolver argument
     *
     * @type string
     */
    const MESSAGE_EXTENSION_FOR_MODEL = ' for the given model';

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
     * Create an exception instance for a problem resolving a model
     *
     * @param mixed $model
     * @param int $code
     * @param Exception|null $previous
     * @return UnresolvableHydratorException
     */
    public static function forModel($model, $code = 0, Exception $previous = null)
    {
        $message = self::DEFAULT_MESSAGE . self::MESSAGE_EXTENSION_FOR_MODEL;

        $message .= sprintf(
            self::MESSAGE_EXTENSION_TYPE_FORMAT,
            is_object($model) ? get_class($model) : gettype($model)
        );

        return new static($message, $code, $previous);
    }
}
