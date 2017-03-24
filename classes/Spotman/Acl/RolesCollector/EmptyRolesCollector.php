<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\Acl;

class EmptyRolesCollector implements RolesCollectorInterface
{
    /**
     * Collect roles from external source and add them to acl via public methods Acl::addRole / Acl::removeRole
     *
     * @param \Spotman\Acl\Acl $acl
     */
    public function collectRoles(Acl $acl)
    {
        // Empty
    }
}
