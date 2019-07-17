<?php
namespace Spotman\Acl\ResourceRulesCollector;

use Spotman\Acl\AclInterface;
use Spotman\Acl\ResourceInterface;

interface ResourceRulesCollectorInterface
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param \Spotman\Acl\AclInterface      $acl
     *
     * @return void
     */
    public function collectResourcePermissions(ResourceInterface $resource, AclInterface $acl): void;
}
