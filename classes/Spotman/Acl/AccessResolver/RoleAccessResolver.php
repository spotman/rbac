<?php
namespace Spotman\Acl\AccessResolver;

use Spotman\Acl\AclInterface;
use Spotman\Acl\AclRoleInterface;
use Spotman\Acl\ResourceInterface;

class RoleAccessResolver implements AclAccessResolverInterface
{
    /**
     * @var \Spotman\Acl\AclInterface
     */
    private $acl;

    /**
     * @var AclRoleInterface
     */
    private $role;

    /**
     * RoleAccessResolver constructor.
     *
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function __construct(AclInterface $acl)
    {
        $this->acl = $acl;
    }

    public function setRole(AclRoleInterface $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param                                $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity): bool
    {
        return $this->acl->isAllowedToRole($resource, $permissionIdentity, $this->role);
    }
}
