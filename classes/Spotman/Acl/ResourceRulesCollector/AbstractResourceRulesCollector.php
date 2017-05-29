<?php
namespace Spotman\Acl\ResourceRulesCollector;

use Spotman\Acl\AclInterface;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;
use Spotman\Acl\ResourceInterface;

abstract class AbstractResourceRulesCollector implements AclRulesCollectorInterface
{
    /**
     * @var \Spotman\Acl\ResourceInterface
     */
    protected $resource;

    /**
     * AbstractResourceRulesCollector constructor.
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
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function collectPermissions(AclInterface $acl)
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
