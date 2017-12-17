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
use DateTimeZone;
use Incoming\Hydrator\AbstractDelegateBuilderHydrator;
use Incoming\Hydrator\AbstractDelegateContextualBuilderHydrator;
use Incoming\Structure\Map;
use PHPUnit\Framework\TestCase;

class AbstractDelegateContextualBuilderHydratorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateBuilderHydrator(callable $delegate): AbstractDelegateContextualBuilderHydrator
    {
        $mock = $this->getMockBuilder(AbstractDelegateContextualBuilderHydrator::class)
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
        $test_context = Map::fromArray(['timezone' => new DateTimeZone('America/Denver')]);

        $test_delegate_callable = function (Map $incoming, Map $context = null): DateTime {
            $model = new DateTime();

            if (null !== $context) {
                // Set timezone before date data, so that we don't recalculate
                $model->setTimezone($context->get('timezone'));
            }

            $model->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_builder = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $built = $test_builder->build($test_input_data, $test_context);

        $this->assertInstanceOf(DateTime::class, $built);
        $this->assertSame($test_input_data['year'], (int) $built->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $built->format('m'));
        $this->assertSame($test_input_data['day'], (int) $built->format('j'));
        $this->assertSame($test_context['timezone']->getName(), $built->getTimezone()->getName());
    }

    public function testHydrate()
    {
        $test_input_data = Map::fromArray([
            'year' => 1983,
            'month' => 1,
            'day' => 2,
        ]);
        $test_model = new DateTime();
        $test_context = Map::fromArray(['timezone' => new DateTimeZone('America/Denver')]);

        $test_delegate_callable = function (Map $incoming, DateTime $model, Map $context = null): DateTime {
            if (null !== $context) {
                // Set timezone before date data, so that we don't recalculate
                $model->setTimezone($context->get('timezone'));
            }

            $model->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_hydrator = $this->getMockDelegateBuilderHydrator($test_delegate_callable);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model, $test_context);

        $this->assertEquals($test_model, $hydrated);
        $this->assertSame($test_input_data['year'], (int) $hydrated->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $hydrated->format('m'));
        $this->assertSame($test_input_data['day'], (int) $hydrated->format('j'));
        $this->assertSame($test_context['timezone']->getName(), $hydrated->getTimezone()->getName());
    }
}
