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
use Incoming\Hydrator\Exception\HydrationProcessException;
use PHPUnit\Framework\TestCase;

class HydrationProcessExceptionTest extends TestCase
{

    public function testMessageIsDefault()
    {
        $exception = new HydrationProcessException();

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame(HydrationProcessException::DEFAULT_MESSAGE, $exception->getMessage());
    }
}
