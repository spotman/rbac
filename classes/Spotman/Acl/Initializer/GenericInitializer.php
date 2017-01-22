<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\Acl;
use Spotman\Acl\Resolver\AccessResolverInterface;
use Spotman\Acl\RolesCollector\RolesCollectorInterface;
use Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface;
use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\ResourceFactory\ResourceFactoryInterface;

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
     * @var AccessResolverInterface
     */
    private $accessResolver;

    /**
     * GenericInitializer constructor.
     *
     * @param \Spotman\Acl\ResourceFactory\ResourceFactoryInterface           $resourceFactory
     * @param \Spotman\Acl\Resolver\AccessResolverInterface                   $accessResolver
     * @param \Spotman\Acl\RolesCollector\RolesCollectorInterface             $rolesCollector
     * @param \Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface     $resourcesCollector
     * @param \Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface $permissionsCollector
     */
    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        AccessResolverInterface $accessResolver,
        RolesCollectorInterface $rolesCollector,
        ResourcesCollectorInterface $resourcesCollector,
        PermissionsCollectorInterface $permissionsCollector
    )
    {
        $this->resourceFactory          = $resourceFactory;
        $this->accessResolver           = $accessResolver;
        $this->rolesCollector           = $rolesCollector;
        $this->resourcesCollector       = $resourcesCollector;
        $this->permissionsCollector     = $permissionsCollector;
    }

    public function init(Acl $acl)
    {
        $acl->addRolesCollector($this->rolesCollector);
        $acl->addResourcesCollector($this->resourcesCollector);
        $acl->addPermissionsCollector($this->permissionsCollector);
        $acl->setResourceFactory($this->resourceFactory);
        $acl->setAccessResolver($this->accessResolver);

        $acl->init();
    }
}
