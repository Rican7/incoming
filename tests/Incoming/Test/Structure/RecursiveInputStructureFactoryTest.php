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

namespace Incoming\Test\Structure;

use ArrayIterator;
use DateTime;
use Incoming\Structure\Exception\InvalidStructuralTypeException;
use Incoming\Structure\FixedList;
use Incoming\Structure\Map;
use Incoming\Structure\RecursiveInputStructureFactory;
use Incoming\Structure\Structure;
use PHPUnit\Framework\TestCase;

class RecursiveInputStructureFactoryTest extends TestCase
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

        $structure = (new RecursiveInputStructureFactory)->build($data);

        // Assert that our types translated correctly
        $this->assertInstanceOf(FixedList::class, $structure);
        $this->assertContainsOnlyInstancesOf('Incoming\Structure\Map', $structure);
        $this->assertInstanceOf(FixedList::class, $structure[0]['stuff']);
    }

    public function testBuildWithTraversable()
    {
        $data = $this->getTestMapArrayData();

        $structure = (new RecursiveInputStructureFactory)->build(new ArrayIterator($data));

        // Assert that our types translated correctly
        $this->assertInstanceOf(Map::class, $structure);
        $this->assertInstanceOf(FixedList::class, $structure['stuff']);
    }

    public function testBuildWithNestedArray()
    {
        $data = [[[], [], []], [], []];

        $structure = (new RecursiveInputStructureFactory)->build($data);

        $this->assertInstanceOf(Structure::class, $structure);
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

        $structure = (new RecursiveInputStructureFactory)->build($data);

        $this->assertInstanceOf(Structure::class, $structure);
    }

    public function testBuildWithInvalidStructuralType()
    {
        $data = new DateTime();

        $this->expectException(InvalidStructuralTypeException::class);

        $structure = (new RecursiveInputStructureFactory)->build($data);
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

        $structure = (new RecursiveInputStructureFactory)->build($data);

        $this->assertInstanceOf(Map::class, $structure);
    }
}
