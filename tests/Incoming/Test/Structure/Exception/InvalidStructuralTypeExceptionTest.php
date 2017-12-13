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

namespace Incoming\Test\Hydrator\Exception;

use DateTime;
use Exception;
use Incoming\Structure\Exception\InvalidStructuralTypeException;
use PHPUnit\Framework\TestCase;

class InvalidStructuralTypeExceptionTest extends TestCase
{

    public function testWithTypeInfo()
    {
        $value = new DateTime();

        $exception = InvalidStructuralTypeException::withTypeInfo($value);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidStructuralTypeException::class, $exception);
    }

    public function testWithTypeInfoWithExceptionArgs()
    {
        $value = new DateTime();
        $code = 1337;
        $previous = new Exception();

        $exception = InvalidStructuralTypeException::withTypeInfo($value, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidStructuralTypeException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
