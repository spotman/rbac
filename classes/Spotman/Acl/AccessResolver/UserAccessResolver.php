<?php
namespace Spotman\Acl\AccessResolver;

use Spotman\Acl\Acl;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\ResourceInterface;

class UserAccessResolver implements AclAccessResolverInterface
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
     * @param \Spotman\Acl\Acl  $acl
     */
    public function __construct(Acl $acl)
    {
        $this->setAcl($acl);
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

    /**
     * @param \Spotman\Acl\Acl $acl
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        $this->user = $acl->getCurrentUser(); // Preset current user as default value
    }

    public function setUser(AclUserInterface $user)
    {
        $this->user = $user;
        return $this;
    }
}
