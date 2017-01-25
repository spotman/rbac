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

    protected function hasRole($roleIdentity)
    {
        return $this->acl->hasRole($roleIdentity);
    }

    protected function addRole($roleIdentity, $parentRolesIdentities = null)
    {
        $this->acl->addRole($roleIdentity, $parentRolesIdentities);
    }

    protected function removeRole($roleIdentity)
    {
        $this->acl->addRole($roleIdentity);
    }
}
