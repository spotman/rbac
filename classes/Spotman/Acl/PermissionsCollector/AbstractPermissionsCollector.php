<?php
namespace Spotman\Acl\PermissionsCollector;

use Spotman\Acl\Acl;

/**
 * Class AbstractPermissionsCollector
 *
 * @package Spotman\Acl
 */
abstract class AbstractPermissionsCollector implements PermissionsCollectorInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * AbstractPermissionsCollector constructor.
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

    protected function addAllowRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity = null)
    {
        $this->acl->addAllowRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity);
    }

    protected function removeAllowRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity = null)
    {
        $this->acl->removeAllowRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity);
    }

    protected function addDenyRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity = null)
    {
        $this->acl->addDenyRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity);
    }

    protected function removeDenyRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity = null)
    {
        $this->acl->removeDenyRule($roleIdentity, $resourceIdentity, $permissionIdentity, $bindToResourceIdentity);
    }
}
