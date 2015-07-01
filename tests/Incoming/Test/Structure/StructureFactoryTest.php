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
use DateTime;
use Incoming\Structure\FixedList;
use Incoming\Structure\Map;
use Incoming\Structure\StructureFactory;
use Incoming\Structure\StructureInterface;
use PHPUnit_Framework_TestCase;

/**
 * StructureFactoryTest
 */
class StructureFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * Helpers
     */

    private function getTestMapArrayData()
    {
        return [
            'name' => 'doge',
            'type' => 'animal',
            'stuff' => [
                'woof',
                'wow',
                'very',
                'such',
                'one of these days I\'ll stop using "doge" in my code tests...',
            ],
        ];
    }

    private function getTestListArrayData()
    {
        return [
            $this->getTestMapArrayData(),
            [
                'name' => 'cat',
                'type' => 'ninja',
                'stuff' => [
                    'meow',
                    'feline',
                    'stealthy',
                ],
            ],
        ];
    }


    /**
     * Tests
     */

    public function testBuildWithArray()
    {
        $data = $this->getTestListArrayData();

        $structure = (new StructureFactory)->build($data);

        // Assert that our types translated correctly
        $this->assertTrue($structure instanceof FixedList);
        $this->assertContainsOnlyInstancesOf('Incoming\Structure\Map', $structure);
        $this->assertTrue($structure[0]['stuff'] instanceof FixedList);
    }

    public function testBuildWithTraversable()
    {
        $data = $this->getTestMapArrayData();

        $structure = (new StructureFactory)->build(new ArrayIterator($data));

        // Assert that our types translated correctly
        $this->assertTrue($structure instanceof Map);
        $this->assertTrue($structure['stuff'] instanceof FixedList);
    }

    public function testBuildWithNestedArray()
    {
        $data = [[[], [], []], [], []];

        $structure = (new StructureFactory)->build($data);

        $this->assertTrue($structure instanceof StructureInterface);
    }

    public function testBuildWithNestedIterators()
    {
        $data = new ArrayIterator([
            new ArrayIterator([
                new ArrayIterator([]),
                new ArrayIterator([]),
                new ArrayIterator([]),
            ]),
            new ArrayIterator([]),
            new ArrayIterator([]),
        ]);

        $structure = (new StructureFactory)->build($data);

        $this->assertTrue($structure instanceof StructureInterface);
    }

    /**
     * @expectedException Incoming\Structure\Exception\InvalidStructuralTypeException
     */
    public function testBuildWithInvalidStructuralType()
    {
        $data = new DateTime();

        $structure = (new StructureFactory)->build($data);
    }

    /**
     * @link https://github.com/Rican7/incoming/pull/3
     */
    public function testBuildWithTraversableAndMixedKeys()
    {
        $data = new ArrayIterator([
            'name' => 'markus',
            0 => 'a crazy mixed-in key'
        ]);

        $structure = (new StructureFactory)->build($data);

        $this->assertTrue($structure instanceof Map);
    }
}
