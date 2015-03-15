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
use Incoming\Structure\Map;
use Iterator;
use MultipleIterator;
use PHPUnit_Framework_TestCase;

/**
 * MapTest
 */
class MapTest extends PHPUnit_Framework_TestCase
{

    /**
     * Helpers
     */

    private function getTestArrayData()
    {
        return [
            'sick' => 'bro',
            'cool' => 'test',
        ];
    }

    private function getTestDataNonExistentKey()
    {
        return 'non-existent-key';
    }


    /**
     * Tests
     */

    public function testFromTraversable()
    {
        $map = Map::fromTraversable(
            new ArrayIterator($this->getTestArrayData())
        );

        $this->assertTrue($map instanceof Map);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFromTraversableFailsForNonScalarKeys()
    {
        if (0 > version_compare(PHP_VERSION, '5.5.0')) {
            $this->markTestSkipped('PHP 5.5 required to test non-scalar traversable keys');
        }

        $test_iterator = new MultipleIterator();
        $test_iterator->attachIterator(
            new ArrayIterator($this->getTestArrayData())
        );

        // Should throw an exception...
        Map::fromTraversable($test_iterator);
    }

    public function testFromArray()
    {
        $map = Map::fromArray($this->getTestArrayData());

        $this->assertTrue($map instanceof Map);
    }

    public function testExists()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);
        $test_invalid_key = $this->getTestDataNonExistentKey();

        $map = Map::fromArray($test_data);

        // Valid key
        $this->assertTrue($map->exists($test_valid_key));

        // Invalid key
        $this->assertFalse($map->exists($test_invalid_key));
    }

    public function testGet()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);
        $test_invalid_key = $this->getTestDataNonExistentKey();
        $default_value = 'wat';

        $map = Map::fromArray($test_data);

        // Same value for valid key
        $this->assertSame($test_data[$test_valid_key], $map->get($test_valid_key));
        $this->assertNotNull($test_valid_key);

        // Default value for invalid key
        $this->assertSame($default_value, $map->get($test_invalid_key, $default_value));
    }

    public function testIsEmpty()
    {
        // Empty
        $map = new Map();
        $this->assertTrue($map->isEmpty());

        // Non-empty
        $map = Map::fromArray($this->getTestArrayData());
        $this->assertFalse($map->isEmpty());
    }

    public function testKeys()
    {
        $test_data = $this->getTestArrayData();
        $test_data_keys = array_keys($test_data);

        $map = Map::fromArray($test_data);

        $this->assertEquals($test_data_keys, $map->keys());
    }

    public function testToArray()
    {
        $test_data = $this->getTestArrayData();

        $map = Map::fromArray($test_data);

        $this->assertEquals($test_data, $map->toArray());
    }

    public function testCount()
    {
        // Zero
        $map = new Map();
        $this->assertCount(0, $map);
        $this->assertSame(0, $map->count());

        // Non-zero
        $test_data = $this->getTestArrayData();
        $map = Map::fromArray($test_data);
        $this->assertCount(count($test_data), $map);
        $this->assertSame(count($test_data), $map->count());
    }

    public function testGetIterator()
    {
        $test_data = $this->getTestArrayData();

        $map = Map::fromArray($test_data);

        $this->assertTrue($map->getIterator() instanceof Iterator);

        foreach ($map->getIterator() as $key => $value) {
            $this->assertSame($test_data[$key], $value);
        }
    }

    public function testOffsetExists()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);
        $test_invalid_key = $this->getTestDataNonExistentKey();

        $map = Map::fromArray($test_data);

        // Valid key
        $this->assertTrue($map->offsetExists($test_valid_key));

        // Invalid key
        $this->assertFalse($map->offsetExists($test_invalid_key));
    }

    public function testOffsetGet()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);

        $map = Map::fromArray($test_data);

        $this->assertSame($test_data[$test_valid_key], $map->offsetGet($test_valid_key));
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testOffsetSet()
    {
        $map = new Map();

        $map->offsetSet('some-key', 'test');
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testOffsetUnset()
    {
        $map = new Map();

        $map->offsetUnset('some-key');
    }

    public function testMagicIsset()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);
        $test_invalid_key = $this->getTestDataNonExistentKey();

        $map = Map::fromArray($test_data);

        // Valid key
        $this->assertTrue(isset($map->{$test_valid_key}));

        // Invalid key
        $this->assertFalse(isset($map->{$test_invalid_key}));
    }

    public function testMagicGet()
    {
        $test_data = $this->getTestArrayData();
        $test_valid_key = key($test_data);

        $map = Map::fromArray($test_data);

        $this->assertSame($test_data[$test_valid_key], $map->{$test_valid_key});
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testMagicSet()
    {
        $map = new Map();

        $map->key = 'test';
    }

    /**
     * @expectedException Incoming\Structure\Exception\ReadOnlyException
     */
    public function testMagicUnset()
    {
        $map = new Map();

        unset($map->key);
    }
}
