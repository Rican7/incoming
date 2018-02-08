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
use Incoming\Hydrator\AbstractDelegateBuilder;
use Incoming\Hydrator\AbstractDelegateContextualBuilder;
use Incoming\Structure\Map;
use PHPUnit\Framework\TestCase;

class AbstractDelegateContextualBuilderTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateBuilder(
        callable $delegate,
        bool $provide_fallback_context = false
    ): AbstractDelegateContextualBuilder {
        return new class($delegate, $provide_fallback_context) extends AbstractDelegateContextualBuilder
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

        $test_builder = $this->getMockDelegateBuilder($test_delegate_callable);

        $built = $test_builder->build($test_input_data, $test_context);

        $this->assertInstanceOf(DateTime::class, $built);
        $this->assertSame($test_input_data['year'], (int) $built->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $built->format('m'));
        $this->assertSame($test_input_data['day'], (int) $built->format('j'));
        $this->assertSame($test_context['timezone']->getName(), $built->getTimezone()->getName());
    }

    public function testBuildProvidesNonNullContext()
    {
        $this->getMockDelegateBuilder(
            function (array $incoming, Map $context = null) {
                $this->assertNotNull($context);
            },
            true
        )->build([], null);

        $this->getMockDelegateBuilder(
            function (array $incoming, Map $context = null) {
                $this->assertNull($context);
            },
            false
        )->build([], null);
    }
}
