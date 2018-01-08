<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\AclInterface;

interface AclRolesCollectorInterface
{
    /**
     * Collect roles from external source and add them to acl via public methods addRole / removeRole
     *
     * @param \Spotman\Acl\AclInterface $acl
     *
     * @return void
     */
    public function collectRoles(AclInterface $acl): void;
}
