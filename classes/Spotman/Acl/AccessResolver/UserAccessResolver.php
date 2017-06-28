<?php
namespace Spotman\Acl\AccessResolver;

use Spotman\Acl\AclInterface;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\ResourceInterface;

class UserAccessResolver implements AclAccessResolverInterface
{
    /**
     * @var \Spotman\Acl\AclInterface
     */
    private $acl;

    /**
     * @var AclUserInterface
     */
    private $user;

    /**
     * UserAccessResolver constructor.
     *
     * @param \Spotman\Acl\AclInterface     $acl
     * @param \Spotman\Acl\AclUserInterface $user
     */
    public function __construct(AclInterface $acl, AclUserInterface $user)
    {
        $this->acl  = $acl;
        $this->user = $user;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, string $permissionIdentity): bool
    {
        return $this->acl->isAllowedToUser($resource, $permissionIdentity, $this->user);
    }

    public function withUser(AclUserInterface $user): UserAccessResolver
    {
        return new self($this->acl, $user);
    }
}
