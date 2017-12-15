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
use Incoming\Hydrator\AbstractDelegateBuilderHydrator;
use Incoming\Hydrator\Exception\InvalidDelegateException;
use Incoming\Structure\Map;
use PHPUnit\Framework\TestCase;
use TypeError;

class AbstractDelegateBuilderHydratorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateBuilderHydrator(callable $delegate)
    {
        $mock = $this->getMockBuilder(AbstractDelegateBuilderHydrator::class)
            ->setMethods([
                AbstractDelegateBuilderHydrator::DEFAULT_DELEGATE_BUILD_METHOD_NAME,
                AbstractDelegateBuilderHydrator::DEFAULT_DELEGATE_HYDRATE_METHOD_NAME
            ])
            ->getMock();

        $mock->expects($this->any())
            ->method(AbstractDelegateBuilderHydrator::DEFAULT_DELEGATE_BUILD_METHOD_NAME)
            ->will($this->returnCallback($delegate));

        $mock->expects($this->any())
            ->method(AbstractDelegateBuilderHydrator::DEFAULT_DELEGATE_HYDRATE_METHOD_NAME)
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

        $test_delegate_callable = function (Map $incoming) {
            $model = (new DateTime())->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_builder = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $built = $test_builder->build($test_input_data);

        $this->assertInstanceOf(DateTime::class, $built);
        $this->assertSame($test_input_data['year'], (int) $built->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $built->format('m'));
        $this->assertSame($test_input_data['day'], (int) $built->format('j'));
    }

    public function testBuildWithNonCallableThrowsException()
    {
        $mock_builder = new class extends AbstractDelegateBuilderHydrator
        {
        };

        $this->expectException(InvalidDelegateException::class);

        $mock_builder->build([]);
    }

    public function testBuildWithImproperTypesCausesTypeError()
    {
        $this->expectException(TypeError::class);

        $test_delegate_callable = function (Map $incoming, DateTime $model) {
        };

        $test_builder = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $test_builder->build([]);
    }

    public function testHydrate()
    {
        $test_input_data = Map::fromArray([
            'year' => 1983,
            'month' => 1,
            'day' => 2,
        ]);
        $test_model = new DateTime();

        $test_delegate_callable = function (Map $incoming, DateTime $model) {
            $model->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_hydrator = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model);

        $this->assertEquals($test_model, $hydrated);
        $this->assertSame($test_input_data['year'], (int) $hydrated->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $hydrated->format('m'));
        $this->assertSame($test_input_data['day'], (int) $hydrated->format('j'));
    }

    public function testHydrateWithNonCallableThrowsException()
    {
        $mock_hydrator = new class extends AbstractDelegateBuilderHydrator
        {
        };

        $this->expectException(InvalidDelegateException::class);

        $mock_hydrator->hydrate([], new DateTime());
    }

    public function testHydrateWithImproperTypesCausesTypeError()
    {
        $this->expectException(TypeError::class);

        $test_delegate_callable = function (Map $incoming, DateTime $model) {
        };

        $test_hydrator = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $test_hydrator->hydrate([], new DateTime());
    }
}
