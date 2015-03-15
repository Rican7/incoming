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

use Incoming\Structure\StructureInterface;
use Incoming\Transformer\StructureBuilderTransformer;
use PHPUnit_Framework_TestCase;

/**
 * StructureBuilderTransformerTest
 */
class StructureBuilderTransformerTest extends PHPUnit_Framework_TestCase
{

    public function testTransform()
    {
        $input = ['test', 'stuff'];

        $output = (new StructureBuilderTransformer())->transform($input);

        $this->assertNotSame($input, $output);

        $this->assertTrue($output instanceof StructureInterface);
    }
}
