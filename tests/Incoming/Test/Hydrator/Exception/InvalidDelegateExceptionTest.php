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
use Incoming\Hydrator\Exception\InvalidDelegateException;
use PHPUnit\Framework\TestCase;

/**
 * InvalidDelegateExceptionTest
 */
class InvalidDelegateExceptionTest extends TestCase
{

    public function testForNonCallable()
    {
        $exception = InvalidDelegateException::forNonCallable();

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidDelegateException::class, $exception);
        $this->assertSame(InvalidDelegateException::CODE_FOR_NON_CALLABLE, $exception->getCode());
    }

    public function testForNonCallableWithName()
    {
        $non_callable_name = 'someNonExistentFunction';

        $exception = InvalidDelegateException::forNonCallable($non_callable_name);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidDelegateException::class, $exception);
        $this->assertSame(InvalidDelegateException::CODE_FOR_NON_CALLABLE, $exception->getCode());
    }

    public function testForNonCallableWithNameAndExceptionArgs()
    {
        $non_callable_name = 'someNonExistentFunction';
        $code = 1337;
        $previous = new Exception();

        $exception = InvalidDelegateException::forNonCallable($non_callable_name, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidDelegateException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
