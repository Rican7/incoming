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
use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use PHPUnit\Framework\TestCase;

class UnresolvableHydratorExceptionTest extends TestCase
{

    public function testForModel()
    {
        $model = new DateTime();

        $exception = UnresolvableHydratorException::forModel($model);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(UnresolvableHydratorException::class, $exception);
        $this->assertSame(UnresolvableHydratorException::CODE_FOR_MODEL, $exception->getCode());
    }

    public function testForModelWithExceptionArgs()
    {
        $model = new DateTime();
        $code = 1337;
        $previous = new Exception();

        $exception = UnresolvableHydratorException::forModel($model, $code, $previous);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(UnresolvableHydratorException::class, $exception);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
