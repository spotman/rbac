<?php
namespace Spotman\Acl\Initializer;

use Psr\Log\LoggerInterface;
use Spotman\Acl\Acl;
use Spotman\Acl\AclUserInterface;
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
     * @var AclUserInterface
     */
    private $user;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * GenericInitializer constructor.
     *
     * @param \Spotman\Acl\ResourceFactory\ResourceFactoryInterface           $resourceFactory
     * @param \Spotman\Acl\Resolver\AccessResolverInterface                   $accessResolver
     * @param \Spotman\Acl\RolesCollector\RolesCollectorInterface             $rolesCollector
     * @param \Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface     $resourcesCollector
     * @param \Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface $permissionsCollector
     * @param LoggerInterface                                                 $logger
     * @param AclUserInterface                                                $user
     */
    public function __construct(
        ResourceFactoryInterface $resourceFactory,
        AccessResolverInterface $accessResolver,
        RolesCollectorInterface $rolesCollector,
        ResourcesCollectorInterface $resourcesCollector,
        PermissionsCollectorInterface $permissionsCollector,
        LoggerInterface $logger,
        AclUserInterface $user
    )
    {
        $this->logger                   = $logger;
        $this->user                     = $user;
        $this->resourceFactory          = $resourceFactory;
        $this->accessResolver           = $accessResolver;
        $this->rolesCollector           = $rolesCollector;
        $this->resourcesCollector       = $resourcesCollector;
        $this->permissionsCollector     = $permissionsCollector;
    }

    public function init(Acl $acl)
    {
        $acl->setLogger($this->logger);
        $acl->addRolesCollector($this->rolesCollector);
        $acl->addResourcesCollector($this->resourcesCollector);
        $acl->addPermissionsCollector($this->permissionsCollector);
        $acl->setResourceFactory($this->resourceFactory);
        $acl->setAccessResolver($this->accessResolver);
    }
}
