<?php
namespace Spotman\Acl\Resolver;

use Spotman\Acl\Acl;
use Spotman\Acl\AclRoleInterface;
use Spotman\Acl\ResourceInterface;

class RoleAccessResolver implements AccessResolverInterface
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
