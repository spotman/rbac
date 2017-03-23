<?php
namespace Spotman\Acl\PermissionsCollector;


class EmptyPermissionsCollector extends AbstractPermissionsCollector
{
    /**
     * Collect permissions from external source and add them to acl via protected methods addAllowRule / addDenyRule
     */
    public function collectPermissions()
    {
        // Empty
    }
}
