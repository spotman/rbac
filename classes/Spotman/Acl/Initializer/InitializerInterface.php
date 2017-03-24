<?php
namespace Spotman\Acl\Initializer;

interface InitializerInterface
{
    /**
     * @return \Spotman\Acl\RolesCollector\RolesCollectorInterface
     */
    public function getRolesCollector();

    /**
     * @return \Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface
     */
    public function getResourcesCollector();

    /**
     * @return \Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface
     */
    public function getPermissionsCollector();

    /**
     * @return \Spotman\Acl\ResourceFactory\ResourceFactoryInterface
     */
    public function getResourceFactory();
}
