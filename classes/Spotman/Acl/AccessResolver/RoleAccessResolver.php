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
        $this->setAcl($acl);
    }

    public function setRole(AclRoleInterface $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Stores Acl instance after unserialize
     *
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function setAcl(AclInterface $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param                                $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity)
    {
        return $this->acl->isAllowedToRole($resource, $permissionIdentity, $this->role);
    }
}
