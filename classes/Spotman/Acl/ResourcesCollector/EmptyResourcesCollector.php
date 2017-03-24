<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\Acl;

class EmptyResourcesCollector implements ResourcesCollectorInterface
{
    /**
     * Collect resources from external source and add them to acl via public methods Acl::addResource / Acl::removeResource
     *
     * @param \Spotman\Acl\Acl $acl
     */
    public function collectResources(Acl $acl)
    {
        // Empty
    }
}
