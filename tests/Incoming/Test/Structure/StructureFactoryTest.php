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

use Incoming\Structure\FixedList;
use Incoming\Structure\Map;
use Incoming\Structure\StructureFactory;
use PHPUnit_Framework_TestCase;

/**
 * StructureFactoryTest
 */
class StructureFactoryTest extends PHPUnit_Framework_TestCase
{

    public function testBuild()
    {
        $data = [
            [
                'name' => 'doge',
                'type' => 'animal',
                'stuff' => [
                    'woof',
                    'wow',
                    'very',
                    'such',
                    'one of these days I\'ll stop using "doge" in my code tests...',
                ],
            ],
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

        $structure = StructureFactory::build($data);

        // Assert that our types translated correctly
        $this->assertTrue($structure instanceof FixedList);
        $this->assertContainsOnlyInstancesOf('Incoming\Structure\Map', $structure);
        $this->assertTrue($structure[0]['stuff'] instanceof FixedList);
    }
}
