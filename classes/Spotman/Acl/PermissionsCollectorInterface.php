<?php
namespace Spotman\Acl;

interface PermissionsCollectorInterface
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     */
    public function collectPermissions();

    /**
     * @param \Spotman\Acl\Acl $acl
     *
     * @return $this
     */
    public function setAcl(Acl $acl);
}
