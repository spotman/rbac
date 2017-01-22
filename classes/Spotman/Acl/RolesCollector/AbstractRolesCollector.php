<?php
namespace Spotman\Acl\RolesCollector;

use Spotman\Acl\Acl;
use Spotman\Acl\AclRoleInterface;

/**
 * Class AbstractRolesCollector
 *
 * @package Spotman\Acl
 */
abstract class AbstractRolesCollector implements RolesCollectorInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * AbstractRolesCollector constructor.
     */
    public function __construct() {}

    /**
     * @param \Spotman\Acl\Acl $acl
     *
     * @return $this
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }

    protected function addRole(AclRoleInterface $role, $parentRoleIdentity = null)
    {
        $this->acl->addRole($role, $parentRoleIdentity);
    }

    protected function removeRole($roleIdentity)
    {
        $this->acl->addRole($roleIdentity);
    }
}
