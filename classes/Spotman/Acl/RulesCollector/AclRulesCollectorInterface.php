<?php
namespace Spotman\Acl\RulesCollector;

use Spotman\Acl\AclInterface;

interface AclRulesCollectorInterface
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     *
     * @param \Spotman\Acl\AclInterface $acl
     *
     * @return void
     */
    public function collectPermissions(AclInterface $acl): void;
}
