<?php
namespace Spotman\Acl\RolesCollector;

class EmptyRolesCollector extends AbstractRolesCollector
{
    /**
     * Collect roles from external source and add them to acl via protected methods addRole / removeRole
     */
    public function collectRoles()
    {
        // Empty
    }
}
