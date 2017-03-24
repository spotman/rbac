<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\Acl;

interface RolesCollectorInterface
{
    /**
     * Collect roles from external source and add them to acl via public methods addRole / removeRole
     *
     * @param \Spotman\Acl\Acl $acl
     *
     * @return
     */
    public function collectRoles(Acl $acl);
}
