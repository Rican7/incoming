<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Test\Structure;

use ArrayIterator;
use Incoming\Structure\FixedList;
use Iterator;
use PHPUnit_Framework_TestCase;

/**
 * FixedListTest
 */
class FixedListTest extends PHPUnit_Framework_TestCase
{

    /**
     * Helpers
     */

    private function getTestArrayData()
    {
        return [
            'nah',
            'sick',
        ];
    }

    private function getTestDataNonExistentIndex()
    {
        return 9999999;
    }


    /**
     * Tests
     */

    public function testFromArray()
    {
        $fixed_list = FixedList::fromArray($this->getTestArrayData());

        $this->assertTrue($fixed_list instanceof FixedList);
    }

    public function testFromTraversable()
    {
        $fixed_list = FixedList::fromTraversable(
            new ArrayIterator($this->getTestArrayData())
        );

        $this->assertTrue($fixed_list instanceof FixedList);
    }

    public function testExists()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_index = key($test_data);
        $test_invalid_index = $this->getTestDataNonExistentIndex();

        $fixed_list = FixedList::fromArray($test_data);

        // Valid index
        $this->assertTrue($fixed_list->exists($test_valid_index));

        // Invalid index
        $this->assertFalse($fixed_list->exists($test_invalid_index));
    }

    public function testGet()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_index = key($test_data);
        $test_invalid_index = $this->getTestDataNonExistentIndex();
        $default_value = 'wat';

        $fixed_list = FixedList::fromArray($test_data);

        // Same value for valid index
        $this->assertSame($test_data[$test_valid_index], $fixed_list->get($test_valid_index));

        // Default value for invalid index
        $this->assertSame($default_value, $fixed_list->get($test_invalid_index, $default_value));
    }

    public function testIsEmpty()
    {
        // Empty
        $fixed_list = new FixedList();
        $this->assertTrue($fixed_list->isEmpty());

        // Non-empty
        $fixed_list = FixedList::fromArray($this->getTestArrayData());
        $this->assertFalse($fixed_list->isEmpty());
    }

    public function testToArray()
    {
        $test_data = $this->getTestArrayData();

        $fixed_list = FixedList::fromArray($test_data);

        $this->assertEquals($test_data, $fixed_list->toArray());
    }

    public function testCount()
    {
        // Zero
        $fixed_list = new FixedList();
        $this->assertCount(0, $fixed_list);
        $this->assertSame(0, $fixed_list->count());

        // Non-zero
        $size = 5;
        $fixed_list = new FixedList($size);
        $this->assertCount($size, $fixed_list);
        $this->assertSame($size, $fixed_list->count());
    }

    public function testGetIterator()
    {
        $test_data = $this->getTestArrayData();

        $fixed_list = FixedList::fromArray($test_data);

        $this->assertTrue($fixed_list->getIterator() instanceof Iterator);

        foreach ($fixed_list->getIterator() as $index => $value) {
            $this->assertSame($test_data[$index], $value);
        }
    }

    public function testOffsetExists()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_index = key($test_data);
        $test_invalid_index = $this->getTestDataNonExistentIndex();

        $fixed_list = FixedList::fromArray($test_data);

        // Valid index
        $this->assertTrue($fixed_list->offsetExists($test_valid_index));

        // Invalid index
        $this->assertFalse($fixed_list->offsetExists($test_invalid_index));
    }

    public function testOffsetGet()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_index = key($test_data);

        $fixed_list = FixedList::fromArray($test_data);

        $this->assertSame($test_data[$test_valid_index], $fixed_list->offsetGet($test_valid_index));
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testOffsetSet()
    {
        $fixed_list = new FixedList(1);

        $fixed_list->offsetSet(0, 'test');
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testOffsetUnset()
    {
        $fixed_list = new FixedList(1);

        $fixed_list->offsetUnset(0);
    }
}
