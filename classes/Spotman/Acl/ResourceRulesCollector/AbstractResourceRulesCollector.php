<?php
namespace Spotman\Acl\ResourceRulesCollector;

use Spotman\Acl\AclInterface;
use Spotman\Acl\ResourceInterface;

abstract class AbstractResourceRulesCollector implements ResourceRulesCollectorInterface
{
    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param \Spotman\Acl\AclInterface      $acl
     */
    public function collectResourcePermissions(ResourceInterface $resource, AclInterface $acl): void
    {
        foreach ($this->getPermissionsRoles($resource) as $permissionIdentity => $roles) {
            foreach ($roles as $roleIdentity) {
                $acl->addAllowRule($roleIdentity, $resource, $permissionIdentity);
            }
        }
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return array[]
     */
    abstract protected function getPermissionsRoles(ResourceInterface $resource): array;
}
