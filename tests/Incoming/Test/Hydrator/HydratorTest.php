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

use Incoming\Hydrator\HydratorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * HydratorTest
 */
class HydratorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockHydratorForStdClass(array $data, stdClass $instance)
    {
        $mock = $this->createMock(HydratorInterface::class);

        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }

        $mock->expects($this->any())
            ->method('hydrate')
            ->will($this->returnValue($instance));

        return $mock;
    }


    /**
     * Tests
     */

    public function testHydrate()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();
        $test_hydrator = $this->getMockHydratorForStdClass($test_input_data, $test_model);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model);

        $this->assertSame($test_model, $hydrated);
        $this->assertSame($test_input_data['misc'], $hydrated->misc);
    }

    public function testHydrateWithEmptyInputData()
    {
        $test_input_data = [];
        $test_model = new stdClass();
        $test_hydrator = $this->getMockHydratorForStdClass($test_input_data, $test_model);

        $hydrated = $test_hydrator->hydrate($test_input_data, $test_model);

        $this->assertSame($test_model, $hydrated);
        $this->assertEmpty((array) $hydrated);
    }
}
