<?php
namespace Spotman\Acl\PermissionsCollector;

use Spotman\Acl\Acl;

class EmptyPermissionsCollector implements PermissionsCollectorInterface
{
    /**
     * Collect permissions from external source and add them to acl via public methods Acl::addAllowRule / Acl::addDenyRule
     *
     * @param \Spotman\Acl\Acl $acl
     */
    public function collectPermissions(Acl $acl)
    {
        // Empty
    }
}
