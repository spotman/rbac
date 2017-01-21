<?php
namespace Spotman\Acl;

interface UserInterface
{
    /**
     * @return string
     */
    public function getAccessControlIdentity();

    /**
     * @return RoleInterface[]|\Traversable
     */
    public function getAccessControlRoles();
}
