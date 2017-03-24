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

    /**
     * serialize() checks if your class has a function with the magic name __sleep.
     * If so, that function is executed prior to any serialization.
     * It can clean up the object and is supposed to return an array with the names of all variables of that object that should be serialized.
     * If the method doesn't return anything then NULL is serialized and E_NOTICE is issued.
     * The intended use of __sleep is to commit pending data or perform similar cleanup tasks.
     * Also, the function is useful if you have very large objects which do not need to be saved completely.
     *
     * @return array|NULL
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.sleep
     */
    public function __sleep()
    {
        return [];
    }
}
