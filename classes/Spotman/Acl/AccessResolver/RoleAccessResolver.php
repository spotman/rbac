<?php
namespace Spotman\Acl\AccessResolver;

use Spotman\Acl\Acl;
use Spotman\Acl\AclRoleInterface;
use Spotman\Acl\ResourceInterface;

class RoleAccessResolver implements AclAccessResolverInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * @var AclRoleInterface
     */
    private $role;

    /**
     * RoleAccessResolver constructor.
     *
     * @param \Spotman\Acl\Acl           $acl
     */
    public function __construct(Acl $acl)
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
     * @param \Spotman\Acl\Acl $acl
     */
    public function setAcl(Acl $acl)
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
