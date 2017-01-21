<?php
namespace Spotman\Acl\Resolver;

use Spotman\Acl\Acl;
use Spotman\Acl\RoleInterface;
use Spotman\Acl\ResourceInterface;

class RoleAccessResolver implements AccessResolverInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * @var RoleInterface
     */
    private $role;

    /**
     * UserAccessResolver constructor.
     *
     * @param \Spotman\Acl\Acl           $acl
     */
    public function __construct(Acl $acl)
    {
        $this->acl  = $acl;
    }

    public function setRole(RoleInterface $role)
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
    public function isAllowed(ResourceInterface $resource, $permissionIdentity)
    {
        return $this->acl->isAllowedToRole($resource, $permissionIdentity, $this->role);
    }
}
