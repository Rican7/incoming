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

    private function getMockDelegateBuilder(callable $delegate): AbstractDelegateContextualBuilder
    {
        $mock = $this->getMockBuilder(AbstractDelegateContextualBuilder::class)
            ->setMethods([AbstractDelegateBuilder::DEFAULT_DELEGATE_METHOD_NAME])
            ->getMock();

        $mock->expects($this->any())
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
}
