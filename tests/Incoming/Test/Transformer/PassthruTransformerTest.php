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

use Incoming\Transformer\PassthruTransformer;
use PHPUnit\Framework\TestCase;

class PassthruTransformerTest extends TestCase
{

    public function testTransform()
    {
        $input = ['test', 'stuff'];

        $output = (new PassthruTransformer())->transform($input);

        $this->assertSame($input, $output);
    }
}
