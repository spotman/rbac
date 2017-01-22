<?php
namespace Spotman\Acl\Resolver;

use Spotman\Acl\Acl;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\ResourceInterface;

class UserAccessResolver implements AccessResolverInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * @var AclUserInterface
     */
    private $user;

    /**
     * UserAccessResolver constructor.
     *
     * @param \Spotman\Acl\Acl              $acl
     * @param \Spotman\Acl\AclUserInterface $user
     */
    public function __construct(Acl $acl, AclUserInterface $user)
    {
        $this->user = $user;
        $this->acl  = $acl;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param                                $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity)
    {
        return $this->acl->isAllowedToUser($resource, $permissionIdentity, $this->user);
    }

    public function setUser(AclUserInterface $user)
    {
        $this->user = $user;
        return $this;
    }
}
