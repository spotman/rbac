<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;

interface AclInitializerInterface
{
    /**
     * @return \Spotman\Acl\RolesCollector\AclRolesCollectorInterface
     */
    public function getRolesCollector();

    /**
     * @return \Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface
     */
    public function getResourcesCollector();

    /**
     * @return \Spotman\Acl\RulesCollector\AclRulesCollectorInterface
     */
    public function getPermissionsCollector();

    /**
     * @return \Spotman\Acl\ResourceFactory\AclResourceFactoryInterface
     */
    public function getResourceFactory();

    /**
     * @return \Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface
     */
    public function getResourceRulesCollectorFactory();

    /**
     * @return \Spotman\Acl\AccessResolver\AclAccessResolverInterface
     */
    public function getDefaultAccessResolver(): AclAccessResolverInterface;
}
