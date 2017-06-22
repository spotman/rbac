<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\ResourceFactory\AclResourceFactoryInterface;
use Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface;
use Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\AclRolesCollectorInterface;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;

class GenericAclInitializer implements AclInitializerInterface
{
    /**
     * @var AclAccessResolverInterface
     */
    private $accessResolver;

    /**
     * @var AclRolesCollectorInterface
     */
    private $rolesCollector;

    /**
     * @var AclResourcesCollectorInterface
     */
    private $resourcesCollector;

    /**
     * @var AclRulesCollectorInterface
     */
    private $permissionsCollector;

    /**
     * @var AclResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var AclResourceRulesCollectorFactoryInterface
     */
    private $resourceRulesCollectorFactory;

    /**
     * GenericAclInitializer constructor.
     *
     * @param \Spotman\Acl\AccessResolver\AclAccessResolverInterface         $accessResolver
     * @param \Spotman\Acl\ResourceFactory\AclResourceFactoryInterface       $resourceFactory
     * @param \Spotman\Acl\RolesCollector\AclRolesCollectorInterface         $rolesCollector
     * @param \Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface $resourcesCollector
     * @param \Spotman\Acl\RulesCollector\AclRulesCollectorInterface         $permissionsCollector
     * @param AclResourceRulesCollectorFactoryInterface                      $ResourceRulesCollectorFactory
     */
    public function __construct(
        AclAccessResolverInterface $accessResolver,
        AclResourceFactoryInterface $resourceFactory,
        AclRolesCollectorInterface $rolesCollector,
        AclResourcesCollectorInterface $resourcesCollector,
        AclRulesCollectorInterface $permissionsCollector,
        AclResourceRulesCollectorFactoryInterface $ResourceRulesCollectorFactory
    ) {
        $this->accessResolver                = $accessResolver;
        $this->resourceFactory               = $resourceFactory;
        $this->rolesCollector                = $rolesCollector;
        $this->resourcesCollector            = $resourcesCollector;
        $this->permissionsCollector          = $permissionsCollector;
        $this->resourceRulesCollectorFactory = $ResourceRulesCollectorFactory;
    }

    /**
     * @return \Spotman\Acl\AccessResolver\AclAccessResolverInterface
     */
    public function getDefaultAccessResolver(): AclAccessResolverInterface {
        return $this->accessResolver;
    }

    /**
     * @return \Spotman\Acl\RolesCollector\AclRolesCollectorInterface
     */
    public function getRolesCollector() {
        return $this->rolesCollector;
    }

    /**
     * @return \Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface
     */
    public function getResourcesCollector() {
        return $this->resourcesCollector;
    }

    /**
     * @return \Spotman\Acl\RulesCollector\AclRulesCollectorInterface
     */
    public function getPermissionsCollector() {
        return $this->permissionsCollector;
    }

    /**
     * @return \Spotman\Acl\ResourceFactory\AclResourceFactoryInterface
     */
    public function getResourceFactory() {
        return $this->resourceFactory;
    }

    /**
     * @return \Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface
     */
    public function getResourceRulesCollectorFactory() {
        return $this->resourceRulesCollectorFactory;
    }
}
