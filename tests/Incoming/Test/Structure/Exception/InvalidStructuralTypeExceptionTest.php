<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Test\Hydrator\Exception;

use DateTime;
use Exception;
use Incoming\Structure\Exception\InvalidStructuralTypeException;
use PHPUnit_Framework_TestCase;

/**
 * InvalidStructuralTypeExceptionTest
 */
class InvalidStructuralTypeExceptionTest extends PHPUnit_Framework_TestCase
{

    public function testWithTypeInfo()
    {
        $value = new DateTime();

        $exception = InvalidStructuralTypeException::withTypeInfo($value);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof InvalidStructuralTypeException);
    }

    public function testWithTypeInfoWithExceptionArgs()
    {
        $value = new DateTime();
        $code = 1337;
        $previous = new Exception();

        $exception = InvalidStructuralTypeException::withTypeInfo($value, $code, $previous);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof InvalidStructuralTypeException);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
