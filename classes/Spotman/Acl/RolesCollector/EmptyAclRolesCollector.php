<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\AclInterface;

class EmptyAclRolesCollector implements AclRolesCollectorInterface
{
    /**
     * Collect roles from external source and add them to acl via public methods Acl::addRole / Acl::removeRole
     *
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function collectRoles(AclInterface $acl): void
    {
        // Empty
    }
}
