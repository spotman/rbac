<?php
namespace Spotman\Acl;

interface ResourceInterface extends \Zend\Permissions\Acl\Resource\ResourceInterface
{
    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    public function getAvailablePermissionIdentities();

    /**
     * Returns default permissions bundled with current resource
     * Key=>Value pairs where key is a permission identity and value is an array of roles
     * Useful for presetting permissions for resources with fixed access control list or permissions based on hard-coded logic
     *
     * @return string[][]
     */
    public function getDefaultAccessList();

    /**
     * Returns true if this resource needs custom permission collector
     *
     * @return bool
     */
    public function isCustomRulesCollectorUsed();
}
