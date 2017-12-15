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

namespace Incoming\Test\Transformer;

use Incoming\Structure\RecursiveInputStructureFactory;
use Incoming\Structure\Structure;
use Incoming\Transformer\StructureBuilderTransformer;
use PHPUnit\Framework\TestCase;

class StructureBuilderTransformerTest extends TestCase
{

    public function testGetSetStructureFactory()
    {
        $test_structure_factory = new RecursiveInputStructureFactory();

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

        $this->assertInstanceOf(Structure::class, $output);
    }
}
