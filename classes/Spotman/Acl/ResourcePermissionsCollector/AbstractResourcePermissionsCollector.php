<?php
namespace Spotman\Acl\ResourcePermissionsCollector;

use Spotman\Acl\Acl;
use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\ResourceInterface;

abstract class AbstractResourcePermissionsCollector implements PermissionsCollectorInterface
{
    /**
     * @var \Spotman\Acl\ResourceInterface
     */
    protected $resource;

    /**
     * AbstractResourcePermissionsCollector constructor.
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Collect entities from external source and add them to acl via protected methods addAllowRule / addDenyRule
     *
     * @param \Spotman\Acl\Acl $acl
     */
    public function collectPermissions(Acl $acl)
    {
        $pairs = $this->getPermissionsRoles();

        foreach ($pairs as $permissionIdentity => $roles) {
            foreach ($roles as $roleIdentity) {
                $acl->addAllowRule($roleIdentity, $this->resource, $permissionIdentity);
            }
        }
    }

    /**
     * @return array[]
     */
    abstract protected function getPermissionsRoles();
}
