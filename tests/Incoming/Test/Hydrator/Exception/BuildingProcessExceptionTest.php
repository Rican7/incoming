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
use Incoming\Hydrator\Exception\BuildingProcessException;
use PHPUnit\Framework\TestCase;

class BuildingProcessExceptionTest extends TestCase
{

    public function testMessageIsDefault()
    {
        $exception = new BuildingProcessException();

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame(BuildingProcessException::DEFAULT_MESSAGE, $exception->getMessage());
    }
}
