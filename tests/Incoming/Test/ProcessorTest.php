<?php
/**
 * Incoming
 *
 * @author    Trevor Suarez (Rican7)
 * @copyright (c) Trevor Suarez
 * @link      https://github.com/Rican7/incoming
 * @license   MIT
 */

namespace Incoming\Test;

use DateTime;
use Incoming\Processor;
use Incoming\Transformer\PassthruTransformer;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * ProcessorTest
 */
class ProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Helpers
     */

    private function getMockHydratorFactory($hydrator_to_return = null)
    {
        $mock = $this->getMockBuilder('Incoming\Hydrator\HydratorFactoryInterface')
            ->getMock();

        if (null !== $hydrator_to_return) {
            $mock->expects($this->any())
                ->method('buildForModel')
                ->will($this->returnValue($hydrator_to_return));
        }

        return $mock;
    }

    private function getMockHydratorForStdClass(array $data, stdClass $instance)
    {
        $mock = $this->getMockBuilder('Incoming\Hydrator\HydratorInterface')
            ->getMock();

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

    /**
     * @expectedException Incoming\Hydrator\Exception\UnresolvableHydratorException
     */
    public function testProcessWithUnresolvableHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();

        $processor = new Processor();

        // Should throw an exception...
        $processor->process($test_input_data, $test_model);
    }
}
