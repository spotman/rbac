<?php
namespace Spotman\Acl;

interface ResourceInterface extends \Laminas\Permissions\Acl\Resource\ResourceInterface
{
    public const SUFFIX = 'Resource';

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    public function getAvailablePermissionIdentities(): array;

    /**
     * Returns default permissions bundled with current resource
     * Key=>Value pairs where key is a permission identity and value is an array of roles
     * Useful for presetting permissions for resources with fixed access control list or permissions based on hard-coded logic
     *
     * @return string[][]
     */
    public function getDefaultAccessList(): array;

    /**
     * Returns true if this resource needs custom permission collector
     *
     * @return bool
     */
    public function isCustomRulesCollectorUsed(): bool;
}
