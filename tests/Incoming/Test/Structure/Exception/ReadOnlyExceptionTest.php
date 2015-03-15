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

use Exception;
use Incoming\Structure\Exception\ReadOnlyException;
use PHPUnit_Framework_TestCase;

/**
 * ReadOnlyExceptionTest
 */
class ReadOnlyExceptionTest extends PHPUnit_Framework_TestCase
{

    public function testForAttribute()
    {
        $name = 'test';

        $exception = ReadOnlyException::forAttribute($name);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof ReadOnlyException);
        $this->assertSame(ReadOnlyException::CODE_FOR_ATTRIBUTE, $exception->getCode());
    }

    public function testForAttributeWithValue()
    {
        $name = 'test';
        $value = 'nah';

        $exception = ReadOnlyException::forAttribute($name, $value);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof ReadOnlyException);
        $this->assertSame(ReadOnlyException::CODE_FOR_ATTRIBUTE, $exception->getCode());
    }

    public function testForAttributeWithValueAndExceptionArgs()
    {
        $name = 'test';
        $value = 'nah';
        $code = 1337;
        $previous = new Exception();

        $exception = ReadOnlyException::forAttribute($name, $value, $code, $previous);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof ReadOnlyException);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
