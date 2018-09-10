<?php
namespace Spotman\Acl;

interface AclUserInterface
{
    /**
     * @return string
     */
    public function getAccessControlIdentity(): string;

    /**
     * @return AclRoleInterface[]|\Traversable
     */
    public function getAccessControlRoles(): array;
}
