<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\AclInterface;

interface AclResourcesCollectorInterface
{
    /**
     * Collect resources from external source and add them to acl via public method Acl::addResource
     *
     * @param \Spotman\Acl\AclInterface $acl
     *
     * @return
     */
    public function collectResources(AclInterface $acl);
}
