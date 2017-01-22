<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\Acl;

interface RolesCollectorInterface
{
    /**
     * Collect roles from external source and add them to acl via protected methods addRole / removeRole
     */
    public function collectRoles();

    /**
     * @param \Spotman\Acl\Acl $acl
     *
     * @return $this
     */
    public function setAcl(Acl $acl);
}
