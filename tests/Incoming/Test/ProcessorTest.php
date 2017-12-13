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

namespace Incoming\Test;

use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use Incoming\Hydrator\HydratorFactoryInterface;
use Incoming\Hydrator\HydratorInterface;
use Incoming\Processor;
use Incoming\Transformer\PassthruTransformer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ProcessorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockHydratorFactory($hydrator_to_return = null)
    {
        $mock = $this->createMock(HydratorFactoryInterface::class);

        if (null !== $hydrator_to_return) {
            $mock->expects($this->any())
                ->method('buildForModel')
                ->will($this->returnValue($hydrator_to_return));
        }

        return $mock;
    }

    private function getMockHydratorForStdClass(array $data, stdClass $instance)
    {
        $mock = $this->createMock(HydratorInterface::class);

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

    public function testGetSetInputTransformer()
    {
        $test_input_transformer = new PassthruTransformer();

        $processor = new Processor();

        $initial_value = $processor->getInputTransformer();

        $processor->setInputTransformer($test_input_transformer);

        $this->assertNotSame($initial_value, $processor->getInputTransformer());
        $this->assertSame($test_input_transformer, $processor->getInputTransformer());
    }

    public function testGetSetHydratorFactory()
    {
        $test_hydrator_factory = $this->getMockHydratorFactory();

        $processor = new Processor();

        $initial_value = $processor->getHydratorFactory();

        $processor->setHydratorFactory($test_hydrator_factory);

        $this->assertNotSame($initial_value, $processor->getHydratorFactory());
        $this->assertSame($test_hydrator_factory, $processor->getHydratorFactory());
    }

    public function testProcess()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();
        $test_hydrator = $this->getMockHydratorForStdClass($test_input_data, $test_model);

        $processor = new Processor();

        $hydrated = $processor->process($test_input_data, $test_model, $test_hydrator);

        $this->assertSame($test_model, $hydrated);
        $this->assertSame($test_input_data['misc'], $hydrated->misc);
    }

    public function testProcessWithAutoResolvedHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();
        $test_hydrator_factory = $this->getMockHydratorFactory(
            $this->getMockHydratorForStdClass($test_input_data, $test_model)
        );

        $processor = new Processor();
        $processor->setHydratorFactory($test_hydrator_factory);

        $hydrated = $processor->process($test_input_data, $test_model);

        $this->assertSame($test_model, $hydrated);
        $this->assertSame($test_input_data['misc'], $hydrated->misc);
    }

    public function testProcessWithUnresolvableHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();

        $processor = new Processor();

        $this->expectException(UnresolvableHydratorException::class);

        $processor->process($test_input_data, $test_model);
    }
}
