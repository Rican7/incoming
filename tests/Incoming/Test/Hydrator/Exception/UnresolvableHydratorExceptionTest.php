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
use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use PHPUnit_Framework_TestCase;

/**
 * UnresolvableHydratorExceptionTest
 */
class UnresolvableHydratorExceptionTest extends PHPUnit_Framework_TestCase
{

    public function testForModel()
    {
        $model = new DateTime();
        $code = 1337;
        $previous = new Exception();

        // Bare
        $exception = UnresolvableHydratorException::forModel($model);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof UnresolvableHydratorException);

        // With exception args
        $exception = UnresolvableHydratorException::forModel($model, $code, $previous);

        $this->assertTrue($exception instanceof Exception);
        $this->assertTrue($exception instanceof UnresolvableHydratorException);
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
