<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\ResourceFactory\ResourceFactoryInterface;
use Spotman\Acl\ResourcePermissionsCollectorFactory\ResourcePermissionsCollectorFactoryInterface;
use Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\RolesCollectorInterface;

class GenericInitializer implements InitializerInterface
{
    /**
     * @var RolesCollectorInterface
     */
    private $rolesCollector;

    /**
     * @var ResourcesCollectorInterface
     */
    private $resourcesCollector;

    /**
     * @var PermissionsCollectorInterface
     */
    private $permissionsCollector;

    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var ResourcePermissionsCollectorFactoryInterface
     */
    private $resourcePermissionsCollectorFactory;

    /**
     * GenericInitializer constructor.
     *
     * @param \Spotman\Acl\ResourceFactory\ResourceFactoryInterface           $resourceFactory
     * @param \Spotman\Acl\RolesCollector\RolesCollectorInterface             $rolesCollector
     * @param \Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface     $resourcesCollector
     * @param \Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface $permissionsCollector
     * @param ResourcePermissionsCollectorFactoryInterface                    $resourcePermissionsCollectorFactory
     */
    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        RolesCollectorInterface $rolesCollector,
        ResourcesCollectorInterface $resourcesCollector,
        PermissionsCollectorInterface $permissionsCollector,
        ResourcePermissionsCollectorFactoryInterface $resourcePermissionsCollectorFactory
    )
    {
        $this->resourceFactory                     = $resourceFactory;
        $this->rolesCollector                      = $rolesCollector;
        $this->resourcesCollector                  = $resourcesCollector;
        $this->permissionsCollector                = $permissionsCollector;
        $this->resourcePermissionsCollectorFactory = $resourcePermissionsCollectorFactory;
    }

    /**
     * @return \Spotman\Acl\RolesCollector\RolesCollectorInterface
     */
    public function getRolesCollector()
    {
        return $this->rolesCollector;
    }

    /**
     * @return \Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface
     */
    public function getResourcesCollector()
    {
        return $this->resourcesCollector;
    }

    /**
     * @return \Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface
     */
    public function getPermissionsCollector()
    {
        return $this->permissionsCollector;
    }

    /**
     * @return \Spotman\Acl\ResourceFactory\ResourceFactoryInterface
     */
    public function getResourceFactory()
    {
        return $this->resourceFactory;
    }

    /**
     * @return \Spotman\Acl\ResourcePermissionsCollectorFactory\ResourcePermissionsCollectorFactoryInterface
     */
    public function getResourcePermissionsCollectorFactory()
    {
        return $this->resourcePermissionsCollectorFactory;
    }
}
