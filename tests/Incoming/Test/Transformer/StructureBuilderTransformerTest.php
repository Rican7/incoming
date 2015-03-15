<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Test\Transformer;

use Incoming\Structure\StructureFactory;
use Incoming\Structure\StructureInterface;
use Incoming\Transformer\StructureBuilderTransformer;
use PHPUnit_Framework_TestCase;

/**
 * StructureBuilderTransformerTest
 */
class StructureBuilderTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testGetSetStructureFactory()
    {
        $test_structure_factory = new StructureFactory();

        $transformer = new StructureBuilderTransformer();

        $initial_value = $transformer->getStructureFactory();

        $transformer->setStructureFactory($test_structure_factory);

        $this->assertNotSame($initial_value, $transformer->getStructureFactory());
        $this->assertSame($test_structure_factory, $transformer->getStructureFactory());
    }

    public function testTransform()
    {
        $input = ['test', 'stuff'];

        $output = (new StructureBuilderTransformer())->transform($input);

        $this->assertNotSame($input, $output);

        $this->assertTrue($output instanceof StructureInterface);
    }
}
