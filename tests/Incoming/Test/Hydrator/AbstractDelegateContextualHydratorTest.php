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
use Incoming\Hydrator\AbstractDelegateContextualHydrator;
use Incoming\Hydrator\AbstractDelegateHydrator;
use Incoming\Structure\Map;
use PHPUnit\Framework\TestCase;

class AbstractDelegateContextualHydratorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateHydrator(
        callable $delegate,
        bool $provide_fallback_context = false
    ): AbstractDelegateContextualHydrator {
        $mock = $this->getMockBuilder(AbstractDelegateContextualHydrator::class)
            ->setConstructorArgs([$provide_fallback_context])
            ->setMethods([AbstractDelegateHydrator::DEFAULT_DELEGATE_METHOD_NAME])
            ->getMock();

        $mock->expects($this->atLeastOnce())
            ->method(AbstractDelegateHydrator::DEFAULT_DELEGATE_METHOD_NAME)
            ->will($this->returnCallback($delegate));

        return $mock;
    }


    /**
     * Tests
     */

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

        $test_hydrator = $this->getMockDelegateHydrator($test_delegate_callable);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model, $test_context);

        $this->assertEquals($test_model, $hydrated);
        $this->assertSame($test_input_data['year'], (int) $hydrated->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $hydrated->format('m'));
        $this->assertSame($test_input_data['day'], (int) $hydrated->format('j'));
        $this->assertSame($test_context['timezone']->getName(), $hydrated->getTimezone()->getName());
    }

    public function testHydrateProvidesNonNullContext()
    {
        $test_model = new DateTime();

        $this->getMockDelegateHydrator(
            function (array $incoming, DateTime $model, Map $context = null) {
                $this->assertNotNull($context);
            },
            true
        )->hydrate([], $test_model, null);

        $this->getMockDelegateHydrator(
            function (array $incoming, DateTime $model, Map $context = null) {
                $this->assertNull($context);
            },
            false
        )->hydrate([], $test_model, null);
    }
}
