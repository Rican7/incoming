<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Test\Hydrator;

use DateTime;
use Incoming\Hydrator\AbstractDelegateHydrator;
use Incoming\Structure\Map;
use Incoming\Test\Hydrator\MockDelegateHydrator;
use PHPUnit_Framework_TestCase;

/**
 * AbstractDelegateHydratorTest
 */
class AbstractDelegateHydratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Helpers
     */

    private function getMockDelegateHydrator(callable $delegate)
    {
        $mock = $this->getMockBuilder('Incoming\Hydrator\AbstractDelegateHydrator')
            ->setMethods([AbstractDelegateHydrator::DEFAULT_DELEGATE_METHOD_NAME])
            ->getMock();

        $mock->expects($this->any())
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

        $test_delegate_callable = function (Map $incoming, DateTime $model) {
            $model->setDate(
                $incoming->get('year'),
                $incoming->get('month'),
                $incoming->get('day')
            );

            return $model;
        };

        $test_hydrator = $this->getMockDelegateHydrator($test_delegate_callable);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model);

        $this->assertEquals($test_model, $hydrated);
        $this->assertSame($test_input_data['year'], (int) $hydrated->format('Y'));
        $this->assertSame($test_input_data['month'], (int) $hydrated->format('m'));
        $this->assertSame($test_input_data['day'], (int) $hydrated->format('j'));
    }

    /**
     * @expectedException Incoming\Hydrator\Exception\InvalidDelegateException
     */
    public function testHydrateWithNonCallableThrowsException()
    {
        $mock_hydrator = new MockDelegateHydrator();

        $mock_hydrator->hydrate([], new DateTime());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testHydrateWithImproperTypesCausesTypeError()
    {
        $test_delegate_callable = function (Map $incoming, DateTime $model) {
        };

        $test_hydrator = $this->getMockDelegateHydrator($test_delegate_callable);

        $test_hydrator->hydrate([], new DateTime());
    }
}
