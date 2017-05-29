<?php
namespace Spotman\Acl\Initializer;

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
}
