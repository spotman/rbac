<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\ResourceFactory\AclResourceFactoryInterface;
use Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface;
use Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\AclRolesCollectorInterface;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;

interface AclInitializerInterface
{
    /**
     * @return \Spotman\Acl\RolesCollector\AclRolesCollectorInterface
     */
    public function getRolesCollector(): AclRolesCollectorInterface;

    /**
     * @return \Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface
     */
    public function getResourcesCollector(): AclResourcesCollectorInterface;

    /**
     * @return \Spotman\Acl\RulesCollector\AclRulesCollectorInterface
     */
    public function getPermissionsCollector(): AclRulesCollectorInterface;

    /**
     * @return \Spotman\Acl\ResourceFactory\AclResourceFactoryInterface
     */
    public function getResourceFactory(): AclResourceFactoryInterface;

    /**
     * @return \Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface
     */
    public function getResourceRulesCollectorFactory(): AclResourceRulesCollectorFactoryInterface;
}
