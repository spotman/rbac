<?php
namespace BetaKiller\RBAC;

/**
 * Class EntityCollector
 * @package BetaKiller\RBAC
 */
abstract class EntityCollector
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    /**
     * @var EntityRegistry
     */
    private $registry;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * EntityCollector constructor.
     *
     * @param \BetaKiller\RBAC\EntityFactory    $entityFactory
     * @param \BetaKiller\RBAC\EntityRegistry   $registry
     * @param \BetaKiller\RBAC\Processor        $processor
     */
    public function __construct(EntityFactory $entityFactory, EntityRegistry $registry, Processor $processor)
    {
        $this->entityFactory = $entityFactory;
        $this->registry      = $registry;
        $this->processor     = $processor;
    }


    /**
     * Collect entities from external source and add them to registry
     */
    abstract public function collect();

    protected function addEntity($name, array $permissions, $parentEntityName = null)
    {
        // TODO массив permissions - это соответствие "role name" - "permission name", а entity хранит только даныне для одной роли, с эим надо что-то делать

        /** @var EntityInterface $instance */
        $instance = $this->entityFactory->createInstance($this->processor, $name, $permissions);

        if ($parentEntityName) {
            $parentEntity = $this->registry->get($parentEntityName);
            $instance->setParent($parentEntity);
        }

        $this->registry->set($instance);

        return $instance;
    }

}
