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

use Incoming\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;
use stdClass;

class HydratorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockHydratorForStdClass(array $data, stdClass $instance)
    {
        $mock = $this->createMock(Hydrator::class);

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
