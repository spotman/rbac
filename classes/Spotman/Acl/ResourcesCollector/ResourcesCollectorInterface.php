<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\Acl;

interface ResourcesCollectorInterface
{
    /**
     * Collect resources from external source and add them to acl via protected methods addResource
     */
    public function collectResources();

    /**
     * @param \Spotman\Acl\Acl $acl
     *
     * @return $this
     */
    public function setAcl(Acl $acl);
}
