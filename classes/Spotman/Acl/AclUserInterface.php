<?php
namespace Spotman\Acl;

interface AclUserInterface
{
    /**
     * @return string
     */
    public function getAccessControlIdentity();

    /**
     * @return AclRoleInterface[]|\Traversable
     */
    public function getAccessControlRoles();
}
