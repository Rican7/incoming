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

use Exception;
use Incoming\Hydrator\Builder;
use Incoming\Hydrator\Exception\IncompatibleProcessException;
use Incoming\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class IncompatibleProcessExceptionTest extends TestCase
{

    public function testForRequiredContextCompatibility()
    {
        $exception = IncompatibleProcessException::forRequiredContextCompatibility();

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(IncompatibleProcessException::class, $exception);
        $this->assertSame(IncompatibleProcessException::CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY, $exception->getCode());
    }

    public function testForRequiredContextCompatibilityWithHydratorProcess()
    {
        $process = $this->createMock(Hydrator::class);

        $exception = IncompatibleProcessException::forRequiredContextCompatibility($process);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(IncompatibleProcessException::class, $exception);
        $this->assertSame(
            IncompatibleProcessException::CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY
            + IncompatibleProcessException::CODE_FOR_HYDRATOR,
            $exception->getCode()
        );
    }

    public function testForRequiredContextCompatibilityWithBuilderProcess()
    {
        $process = $this->createMock(Builder::class);

        $exception = IncompatibleProcessException::forRequiredContextCompatibility($process);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(IncompatibleProcessException::class, $exception);
        $this->assertSame(
            IncompatibleProcessException::CODE_FOR_REQUIRED_CONTEXT_COMPATIBILITY
            + IncompatibleProcessException::CODE_FOR_BUILDER,
            $exception->getCode()
        );
    }

    public function testForRequiredContextCompatibilityWithProcessAndExceptionArgs()
    {
        $process = $this->createMock(Hydrator::class);
        $code = 1337;
        $previous = new Exception();

        $exception = IncompatibleProcessException::forRequiredContextCompatibility($process, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(IncompatibleProcessException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
