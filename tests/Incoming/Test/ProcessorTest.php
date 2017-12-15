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

use Incoming\Hydrator\Builder;
use Incoming\Hydrator\BuilderFactory;
use Incoming\Hydrator\Exception\UnresolvableBuilderException;
use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use Incoming\Hydrator\Hydrator;
use Incoming\Hydrator\HydratorFactory;
use Incoming\Processor;
use Incoming\Transformer\PassthruTransformer;
use PHPUnit\Framework\TestCase;
use stdClass;

class ProcessorTest extends TestCase
{

    /**
     * Helpers
     */

    private function getMockHydratorFactory(Hydrator $hydrator_to_return = null): HydratorFactory
    {
        $mock = $this->createMock(HydratorFactory::class);

        if (null !== $hydrator_to_return) {
            $mock->expects($this->once())
                ->method('buildForModel')
                ->will($this->returnValue($hydrator_to_return));
        }

        return $mock;
    }

    private function getMockHydratorForStdClass(array $data, stdClass $instance): Hydrator
    {
        $mock = $this->createMock(Hydrator::class);

        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }

        $mock->expects($this->once())
            ->method('hydrate')
            ->will($this->returnValue($instance));

        return $mock;
    }

    private function getMockBuilderFactory(Builder $builder_to_return = null): BuilderFactory
    {
        $mock = $this->createMock(BuilderFactory::class);

        if (null !== $builder_to_return) {
            $mock->expects($this->once())
                ->method('buildForType')
                ->will($this->returnValue($builder_to_return));
        }

        return $mock;
    }

    private function getMockBuilderForStdClass(array $data): Builder
    {
        $mock = $this->createMock(Builder::class);

        $instance = new stdClass();

        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }

        $mock->expects($this->once())
            ->method('build')
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

    public function testGetSetBuilderFactory()
    {
        $test_builder_factory = $this->getMockBuilderFactory();

        $processor = new Processor();

        $initial_value = $processor->getBuilderFactory();

        $processor->setBuilderFactory($test_builder_factory);

        $this->assertNotSame($initial_value, $processor->getBuilderFactory());
        $this->assertSame($test_builder_factory, $processor->getBuilderFactory());
    }

    public function testGetSetAlwaysHydrateAfterBuilding()
    {
        $test_value = true;

        $processor = new Processor();

        $initial_value = $processor->getAlwaysHydrateAfterBuilding();

        $processor->setAlwaysHydrateAfterBuilding($test_value);

        $this->assertNotSame($initial_value, $processor->getAlwaysHydrateAfterBuilding());
        $this->assertSame($test_value, $processor->getAlwaysHydrateAfterBuilding());
    }

    public function testProcessForModel()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();
        $test_hydrator = $this->getMockHydratorForStdClass($test_input_data, $test_model);

        $processor = new Processor();

        $hydrated = $processor->processForModel($test_input_data, $test_model, $test_hydrator);

        $this->assertSame($test_model, $hydrated);
        $this->assertSame($test_input_data['misc'], $hydrated->misc);
    }

    public function testProcessForModelWithAutoResolvedHydrator()
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

        $hydrated = $processor->processForModel($test_input_data, $test_model);

        $this->assertSame($test_model, $hydrated);
        $this->assertSame($test_input_data['misc'], $hydrated->misc);
    }

    public function testProcessForModelWithUnresolvableHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_model = new stdClass();

        $processor = new Processor();

        $this->expectException(UnresolvableHydratorException::class);

        $processor->processForModel($test_input_data, $test_model);
    }

    public function testProcessForType()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;
        $test_builder = $this->getMockBuilderForStdClass($test_input_data);

        $processor = new Processor();

        $built = $processor->processForType($test_input_data, $test_type, $test_builder);

        $this->assertInstanceOf($test_type, $built);
        $this->assertSame($test_input_data['misc'], $built->misc);
    }

    public function testProcessForTypeWithBuilderAndHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;
        $test_builder = $this->getMockBuilderForStdClass($test_input_data);
        $test_hydrator = $this->getMockHydratorForStdClass($test_input_data, new stdClass());

        $processor = new Processor();

        $built = $processor->processForType($test_input_data, $test_type, $test_builder, $test_hydrator);

        $this->assertInstanceOf($test_type, $built);
        $this->assertSame($test_input_data['misc'], $built->misc);
    }

    public function testProcessForTypeWithAutoResolvedBuilder()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;
        $test_builder_factory = $this->getMockBuilderFactory(
            $this->getMockBuilderForStdClass($test_input_data)
        );

        $processor = new Processor();
        $processor->setBuilderFactory($test_builder_factory);

        $built = $processor->processForType($test_input_data, $test_type);

        $this->assertInstanceOf($test_type, $built);
        $this->assertSame($test_input_data['misc'], $built->misc);
    }

    public function testProcessForTypeWithAlwaysUseHydratorAndAutoResolvedHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;
        $test_builder = $this->getMockBuilderForStdClass($test_input_data);
        $test_hydrator_factory = $this->getMockHydratorFactory(
            $this->getMockHydratorForStdClass($test_input_data, new stdClass())
        );

        $processor = new Processor();
        $processor->setHydratorFactory($test_hydrator_factory);
        $processor->setAlwaysHydrateAfterBuilding(true);

        $built = $processor->processForType($test_input_data, $test_type, $test_builder);

        $this->assertInstanceOf($test_type, $built);
        $this->assertSame($test_input_data['misc'], $built->misc);
    }

    public function testProcessForTypeWithUnresolvableBuilder()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;

        $processor = new Processor();

        $this->expectException(UnresolvableBuilderException::class);

        $processor->processForType($test_input_data, $test_type);
    }

    public function testProcessForTypeWithAlwaysUseHydratorAndUnresolvableHydrator()
    {
        $test_input_data = [
            'prop_1' => 'thing',
            'prop_2' => 'stuff',
            'misc' => 'yea, sure',
        ];
        $test_type = stdClass::class;
        $test_builder = $this->getMockBuilderForStdClass($test_input_data);

        $processor = new Processor();
        $processor->setAlwaysHydrateAfterBuilding(true);

        $this->expectException(UnresolvableHydratorException::class);

        $processor->processForType($test_input_data, $test_type, $test_builder);
    }
}
