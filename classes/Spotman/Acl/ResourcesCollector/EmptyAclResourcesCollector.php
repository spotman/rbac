<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\AclInterface;

class EmptyAclResourcesCollector implements AclResourcesCollectorInterface
{
    /**
     * Collect resources from external source and add them to acl via public methods Acl::addResource / Acl::removeResource
     *
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function collectResources(AclInterface $acl): void
    {
        // Empty
    }
}
