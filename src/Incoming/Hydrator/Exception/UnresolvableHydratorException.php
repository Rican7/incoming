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
     * @param Exception $previous
     * @return UnresolvableHydratorException
     */
    public static function forModel($model, $code = 0, Exception $previous = null)
    {
        $message = self::DEFAULT_MESSAGE . ' for the given model';

        return new static($message, $code, $previous);
    }
}
