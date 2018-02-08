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

namespace Incoming;

use Incoming\Hydrator\Builder;
use Incoming\Hydrator\BuilderFactory;
use Incoming\Hydrator\ContextualBuilder;
use Incoming\Hydrator\ContextualHydrator;
use Incoming\Hydrator\Exception\IncompatibleProcessException;
use Incoming\Hydrator\Exception\UnresolvableBuilderException;
use Incoming\Hydrator\Exception\UnresolvableHydratorException;
use Incoming\Hydrator\Hydrator;
use Incoming\Hydrator\HydratorFactory;
use Incoming\Structure\Map;
use Incoming\Transformer\StructureBuilderTransformer;
use Incoming\Transformer\Transformer;

/**
 * A default implementation of both the `ModelProcessor` and `TypeProcessor`
 * for processing input data with an optional input transformation phase and
 * automatic hydrator and builder resolution.
 */
class Processor implements ModelProcessor, TypeProcessor
{

    /**
     * Properties
     */

    /**
     * An input transformer to pre-process the input data before hydration.
     *
     * @var Transformer
     */
    private $input_transformer;

    /**
     * A factory for building hydrators for a given model.
     *
     * @var HydratorFactory|null
     */
    private $hydrator_factory;

    /**
     * A factory for building builders for a given model.
     *
     * @var BuilderFactory|null
     */
    private $builder_factory;

    /**
     * A configuration flag that denotes whether hydration should always be run
     * after building a new model when processing specified types.
     *
     * @var bool
     */
    private $always_hydrate_after_building = false;

    /**
     * A configuration flag that denotes whether processing (hydration/building)
     * should require contextual compatibility when a context is provided.
     *
     * @var bool
     */
    private $require_contextual_processing_compatibility = false;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @param Transformer|null $input_transformer The input transformer.
     * @param HydratorFactory|null $hydrator_factory A hydrator factory.
     * @param BuilderFactory|null $builder_factory A builder factory.
     * @param bool $always_hydrate_after_building A configuration flag that
     *  denotes whether hydration should always be run after building a new
     *  model when processing specified types.
     * @param bool $require_contextual_processing_compatibility A configuration
     *  flag that denotes whether processing (hydration/building) should require
     *  contextual compatibility when a context is provided.
     */
    public function __construct(
        Transformer $input_transformer = null,
        HydratorFactory $hydrator_factory = null,
        BuilderFactory $builder_factory = null,
        bool $always_hydrate_after_building = false,
        bool $require_contextual_processing_compatibility = false
    ) {
        $this->input_transformer = $input_transformer ?: new StructureBuilderTransformer();
        $this->hydrator_factory = $hydrator_factory;
        $this->builder_factory = $builder_factory;
        $this->always_hydrate_after_building = $always_hydrate_after_building;
        $this->require_contextual_processing_compatibility = $require_contextual_processing_compatibility;
    }

    /**
     * Get the input transformer.
     *
     * @return Transformer The input transformer.
     */
    public function getInputTransformer(): Transformer
    {
        return $this->input_transformer;
    }

    /**
     * Set the input transformer.
     *
     * @param Transformer $input_transformer The input transformer.
     * @return $this This instance.
     */
    public function setInputTransformer(Transformer $input_transformer): self
    {
        $this->input_transformer = $input_transformer;

        return $this;
    }

    /**
     * Get the hydrator factory.
     *
     * @return HydratorFactory|null The hydrator factory.
     */
    public function getHydratorFactory()
    {
        return $this->hydrator_factory;
    }

    /**
     * Set the hydrator factory.
     *
     * @param HydratorFactory|null $hydrator_factory The hydrator factory.
     * @return $this This instance.
     */
    public function setHydratorFactory(HydratorFactory $hydrator_factory = null): self
    {
        $this->hydrator_factory = $hydrator_factory;

        return $this;
    }

    /**
     * Get the builder factory.
     *
     * @return BuilderFactory|null The builder factory.
     */
    public function getBuilderFactory()
    {
        return $this->builder_factory;
    }

    /**
     * Set the builder factory.
     *
     * @param BuilderFactory|null $builder_factory The builder factory.
     * @return $this This instance.
     */
    public function setBuilderFactory(BuilderFactory $builder_factory = null): self
    {
        $this->builder_factory = $builder_factory;

        return $this;
    }

    /**
     * Get the value of the configuration flag that denotes whether hydration
     * should always be run after building a new model when processing
     * specified types.
     *
     * @return bool The value of the flag.
     */
    public function getAlwaysHydrateAfterBuilding(): bool
    {
        return $this->always_hydrate_after_building;
    }

    /**
     * Set the value of the configuration flag that denotes whether hydration
     * should always be run after building a new model when processing
     * specified types.
     *
     * @param bool $always_hydrate_after_building Whether or not to always
     *  hydrate after building a new model when processing types.
     * @return $this This instance.
     */
    public function setAlwaysHydrateAfterBuilding(bool $always_hydrate_after_building): self
    {
        $this->always_hydrate_after_building = $always_hydrate_after_building;

        return $this;
    }

    /**
     * Get the value of the configuration flag that denotes whether processing
     * (hydration/building) should require contextual compatibility when a
     * context is provided.
     *
     * @return bool The value of the flag.
     */
    public function getRequireContextualProcessingCompatibility(): bool
    {
        return $this->require_contextual_processing_compatibility;
    }

    /**
     * Set the value of the configuration flag that denotes whether processing
     * (hydration/building) should require contextual compatibility when a
     * context is provided.
     *
     * @param bool $require_contextual_processing_compatibility Whether or not
     *  to require contextual processing compatibility when a context is
     *  provided.
     * @return $this This instance.
     */
    public function setRequireContextualProcessingCompatibility(bool $require_contextual_processing_compatibility): self
    {
        $this->require_contextual_processing_compatibility = $require_contextual_processing_compatibility;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * If a hydrator isn't provided, an attempt will be made to automatically
     * resolve and build an appropriate hydrator from the provided factory.
     *
     * @param mixed $input_data The input data.
     * @param mixed $model The model to hydrate.
     * @param Hydrator|null $hydrator The hydrator to use in the process.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the process.
     * @return mixed The hydrated model.
     */
    public function processForModel($input_data, $model, Hydrator $hydrator = null, Map $context = null)
    {
        $input_data = $this->transformInput($input_data);

        return $this->hydrateModel($input_data, $model, $hydrator, $context);
    }

    /**
     * {@inheritdoc}
     *
     * If a builder isn't provided, an attempt will be made to automatically
     * resolve and build an appropriate builder from the provided factory.
     *
     * If a hydrator is provided, it will be used to hydrate the provided type
     * after building via the builder.
     *
     * If a hydrator isn't provided, but the "always_hydrate_after_building"
     * property is set to true, an attempt to hydrate the type will be made
     * after building via the builder, and the hydrator will be automatically
     * resolved from the provided factory.
     *
     * @param mixed $input_data The input data.
     * @param string $type The type to build.
     * @param Builder|null $builder The builder to use in the process.
     * @param Hydrator|null $hydrator An optional hydrator to use in the
     *  process, after the type is built, to aid in the full hydration of the
     *  resulting model.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the process.
     * @return mixed The built model.
     */
    public function processForType(
        $input_data,
        string $type,
        Builder $builder = null,
        Hydrator $hydrator = null,
        Map $context = null
    ) {
        $input_data = $this->transformInput($input_data);

        $model = $this->buildModel($input_data, $type, $builder, $context);

        if (null !== $hydrator || $this->always_hydrate_after_building) {
            $model = $this->hydrateModel($input_data, $model, $hydrator, $context);
        }

        return $model;
    }

    /**
     * Transform the input data.
     *
     * @param mixed $input_data The input data.
     * @return mixed The resulting transformed data.
     */
    protected function transformInput($input_data)
    {
        return $this->input_transformer->transform($input_data);
    }

    /**
     * Hydrate a model from incoming data.
     *
     * If a hydrator isn't provided, an attempt will be made to automatically
     * resolve and build an appropriate hydrator from the provided factory.
     *
     * @param mixed $input_data The input data.
     * @param mixed $model The model to hydrate.
     * @param Hydrator|null $hydrator The hydrator to use.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the process.
     * @return mixed The hydrated model.
     */
    protected function hydrateModel($input_data, $model, Hydrator $hydrator = null, Map $context = null)
    {
        $hydrator = $hydrator ?: $this->getHydratorForModel($model);

        $this->enforceProcessCompatibility(($hydrator instanceof ContextualHydrator), (null !== $context), $hydrator);

        if ($hydrator instanceof ContextualHydrator) {
            return $hydrator->hydrate($input_data, $model, $context);
        }

        return $hydrator->hydrate($input_data, $model);
    }

    /**
     * Build a model from incoming data.
     *
     * If a builder isn't provided, an attempt will be made to automatically
     * resolve and build an appropriate builder from the provided factory.
     *
     * @param mixed $input_data The input data.
     * @param string $type The type to build.
     * @param Builder|null $builder The builder to use.
     * @param Map|null $context An optional generic key-value map, for providing
     *  contextual values during the process.
     * @return mixed The built model.
     */
    protected function buildModel($input_data, string $type, Builder $builder = null, Map $context = null)
    {
        $builder = $builder ?: $this->getBuilderForType($type);

        $this->enforceProcessCompatibility(($builder instanceof ContextualBuilder), (null !== $context), $builder);

        if ($builder instanceof ContextualBuilder) {
            return $builder->build($input_data, $context);
        }

        return $builder->build($input_data);
    }

    /**
     * Get a Hydrator for a given model.
     *
     * @param mixed $model The model to get a hydrator for.
     * @throws UnresolvableHydratorException If a hydrator can't be resolved for
     *  the given model.
     * @return Hydrator The resulting hydrator.
     */
    protected function getHydratorForModel($model): Hydrator
    {
        if (null === $this->hydrator_factory) {
            throw UnresolvableHydratorException::forModel($model);
        }

        return $this->hydrator_factory->buildForModel($model);
    }

    /**
     * Get a Builder for a given model.
     *
     * @param string $type The type to get a builder for.
     * @throws UnresolvableBuilderException If a builder can't be resolved for
     *  the given model.
     * @return Builder The resulting builder.
     */
    protected function getBuilderForType(string $type): Builder
    {
        if (null === $this->builder_factory) {
            throw UnresolvableBuilderException::forType($type);
        }

        return $this->builder_factory->buildForType($type);
    }

    /**
     * Enforce that a provided process (hydrator, builder, etc) is compatible
     * with the processing strategy being used.
     *
     * @param bool $is_context_compatible Whether or not the process is
     *  compatible with contexts.
     * @param bool $context_provided Whether or not a context has been provided
     *  in the process.
     * @param object|null $process The process to enforce compatibility for.
     * @throws IncompatibleProcessException If the builder isn't compatible with
     *  the given process strategy.
     * @return void
     */
    protected function enforceProcessCompatibility(bool $is_context_compatible, bool $context_provided, $process = null)
    {
        if ($context_provided && !$is_context_compatible
            && $this->require_contextual_processing_compatibility) {
            throw IncompatibleProcessException::forRequiredContextCompatibility($process);
        }
    }
}
