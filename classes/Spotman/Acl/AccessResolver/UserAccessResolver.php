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
     * @param \Spotman\Acl\AclInterface $acl
     */
    public function __construct(AclInterface $acl)
    {
        $this->setAcl($acl);
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

    /**
     * @param \Spotman\Acl\AclInterface $acl
     */
    private function setAcl(AclInterface $acl): void
    {
        $this->acl  = $acl;
        $this->user = $acl->getCurrentUser(); // Preset current user as default value
    }

    public function setUser(AclUserInterface $user)
    {
        $this->user = $user;

        return $this;
    }
}
