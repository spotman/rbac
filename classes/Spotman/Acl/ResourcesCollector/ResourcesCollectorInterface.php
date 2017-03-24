<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\Acl;

interface ResourcesCollectorInterface
{
    /**
     * Collect resources from external source and add them to acl via public method Acl::addResource
     *
     * @param \Spotman\Acl\Acl $acl
     *
     * @return
     */
    public function collectResources(Acl $acl);
}
