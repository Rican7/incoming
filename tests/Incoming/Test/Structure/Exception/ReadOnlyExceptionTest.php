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

namespace Incoming\Test\Structure\Exception;

use Exception;
use Incoming\Structure\Exception\ReadOnlyException;
use PHPUnit\Framework\TestCase;

class ReadOnlyExceptionTest extends TestCase
{

    public function testForAttribute()
    {
        $name = 'test';

        $exception = ReadOnlyException::forAttribute($name);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(ReadOnlyException::class, $exception);
        $this->assertSame(ReadOnlyException::CODE_FOR_ATTRIBUTE, $exception->getCode());
    }

    public function testForAttributeWithValue()
    {
        $name = 'test';
        $value = 'nah';

        $exception = ReadOnlyException::forAttribute($name, $value);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(ReadOnlyException::class, $exception);
        $this->assertSame(ReadOnlyException::CODE_FOR_ATTRIBUTE, $exception->getCode());
    }

    public function testForAttributeWithValueAndExceptionArgs()
    {
        $name = 'test';
        $value = 'nah';
        $code = 1337;
        $previous = new Exception();

        $exception = ReadOnlyException::forAttribute($name, $value, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(ReadOnlyException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
