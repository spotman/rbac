<?php
namespace BetaKiller\RBAC;

class Processor
{
    private $permissionNameDelimiter = '_';

    /**
     * @var EntityRegistry
     */
    private $entityRegistry;

    /**
     * @var EntityCollector[]
     */
    private $entityCollectors;

    /**
     * @var PermissionsCache
     */
    private $permissionsCache;

    /**
     * Processor constructor.
     *
     * @param \BetaKiller\RBAC\EntityRegistry   $entityRegistry
     * @param \BetaKiller\RBAC\PermissionsCache $permissionsCache
     */
    public function __construct(EntityRegistry $entityRegistry, PermissionsCache $permissionsCache)
    {
        $this->entityRegistry = $entityRegistry;
        $this->permissionsCache = $permissionsCache;
    }

    public function registerEntityCollector(EntityCollector $collector)
    {
        $this->entityCollectors[] = $collector;
        return $this;
    }

    public function registerEntity(EntityInterface $entity)
    {
        $this->entityRegistry->set($entity);
        return $this;
    }

    public function process()
    {
        // Load cache table
        $cached = [];

        // If empty
        if (!$cached) {
            // Collect all entities
            foreach ($this->entityCollectors as $entityCollector) {
                $entityCollector->collect();
            }

            // Make cache table

            // Save cache table
        }
    }



    public function isGranted($permissionName, RoleInterface $role)
    {
        $this->permissionsCache->get()

        // TODO implement real check
        return true;
    }


    public function getPermissionNameDelimiter()
    {
        return $this->permissionNameDelimiter;
    }
}
