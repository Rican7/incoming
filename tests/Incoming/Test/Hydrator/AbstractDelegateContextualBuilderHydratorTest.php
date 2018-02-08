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

    private function getMockDelegateBuilderHydrator(
        callable $delegate,
        bool $provide_fallback_context = false
    ): AbstractDelegateContextualBuilderHydrator {
        return new class($delegate, $provide_fallback_context) extends AbstractDelegateContextualBuilderHydrator
        {
            private $delegate;

            public function __construct(callable $delegate, bool $provide_fallback_context)
            {
                parent::__construct($provide_fallback_context);

                $this->delegate = $delegate;
            }

            protected function buildModel($incoming, Map $context = null)
            {
                return ($this->delegate)($incoming, $context);
            }

            protected function hydrateModel($incoming, $model, Map $context = null)
            {
                return ($this->delegate)($incoming, $model, $context);
            }
        };
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

    public function testBuildProvidesNonNullContext()
    {
        $this->getMockDelegateBuilderHydrator(
            function (array $incoming, Map $context = null) {
                $this->assertNotNull($context);
            },
            true
        )->build([], null);

        $this->getMockDelegateBuilderHydrator(
            function (array $incoming, Map $context = null) {
                $this->assertNull($context);
            },
            false
        )->build([], null);
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

    public function testHydrateProvidesNonNullContext()
    {
        $test_model = new DateTime();

        $this->getMockDelegateBuilderHydrator(
            function (array $incoming, DateTime $model, Map $context = null) {
                $this->assertNotNull($context);
            },
            true
        )->hydrate([], $test_model, null);

        $this->getMockDelegateBuilderHydrator(
            function (array $incoming, DateTime $model, Map $context = null) {
                $this->assertNull($context);
            },
            false
        )->hydrate([], $test_model, null);
    }
}
