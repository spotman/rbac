<?php
namespace Spotman\Acl\PermissionsCollector;


class EmptyPermissionsCollector extends AbstractPermissionsCollector
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     */
    public function collectPermissions()
    {
        // Empty
    }
}
