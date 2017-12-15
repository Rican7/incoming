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
use Incoming\Hydrator\Exception\UnresolvableBuilderException;
use PHPUnit\Framework\TestCase;

class UnresolvableBuilderExceptionTest extends TestCase
{

    public function testForType()
    {
        $type = DateTime::class;

        $exception = UnresolvableBuilderException::forType($type);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(UnresolvableBuilderException::class, $exception);
        $this->assertSame(UnresolvableBuilderException::CODE_FOR_TYPE, $exception->getCode());
    }

    public function testForTypeWithExceptionArgs()
    {
        $type = DateTime::class;
        $code = 1337;
        $previous = new Exception();

        $exception = UnresolvableBuilderException::forType($type, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(UnresolvableBuilderException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
