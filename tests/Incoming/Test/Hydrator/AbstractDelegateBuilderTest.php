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

namespace Incoming\Test\Hydrator;

use DateTime;
use Incoming\Hydrator\AbstractDelegateBuilder;
use Incoming\Hydrator\Exception\InvalidDelegateException;
use Incoming\Structure\Map;
use PHPUnit\Framework\TestCase;
use TypeError;

class AbstractDelegateBuilderTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateBuilder(callable $delegate): AbstractDelegateBuilder
    {
        $mock = $this->getMockBuilder(AbstractDelegateBuilder::class)
            ->setMethods([AbstractDelegateBuilder::DEFAULT_DELEGATE_METHOD_NAME])
            ->getMock();

        $mock->expects($this->atLeastOnce())
            ->method(AbstractDelegateBuilder::DEFAULT_DELEGATE_METHOD_NAME)
            ->will($this->returnCallback($delegate));

        return $mock;
    }


    /**
     * Tests
     */

    public function testBuild()
    {
        $test_input_data = Map::fromArray([
            'year' => 1983,
            'month' => 1,
            'day' => 2,
        ]);

        $test_delegate_callable = function (Map $incoming): DateTime {
            $model = (new DateTime())->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_builder = $this->getMockDelegateBuilder($test_delegate_callable);

        $built = $test_builder->build($test_input_data);

        $this->assertInstanceOf(DateTime::class, $built);
        $this->assertSame($test_input_data['year'], (int) $built->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $built->format('m'));
        $this->assertSame($test_input_data['day'], (int) $built->format('j'));
    }

    public function testBuildWithNonCallableThrowsException()
    {
        $mock_builder = new class extends AbstractDelegateBuilder
        {
        };

        $this->expectException(InvalidDelegateException::class);

        $mock_builder->build([]);
    }

    public function testBuildWithImproperTypesCausesTypeError()
    {
        $this->expectException(TypeError::class);

        $test_delegate_callable = function (Map $incoming, DateTime $model): DateTime {
        };

        $test_builder = $this->getMockDelegateBuilder($test_delegate_callable);

        $test_builder->build([]);
    }
}
