<?php
namespace Spotman\Acl\RulesCollector;

use Spotman\Acl\AclInterface;

class EmptyAclRulesCollector implements AclRulesCollectorInterface
{
    /**
     * Collect permissions from external source and add them to acl via public methods Acl::addAllowRule / Acl::addDenyRule
     *
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function collectPermissions(AclInterface $acl): void
    {
        // Empty
    }
}
