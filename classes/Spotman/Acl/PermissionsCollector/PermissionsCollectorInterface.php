<?php
namespace Spotman\Acl\PermissionsCollector;

use Spotman\Acl\Acl;

interface PermissionsCollectorInterface
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     *
     * @param \Spotman\Acl\Acl $acl
     *
     * @return
     */
    public function collectPermissions(Acl $acl);
}
