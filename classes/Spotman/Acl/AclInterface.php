<?php
namespace Spotman\Acl;

use Psr\Log\LoggerInterface;
use Spotman\Acl\Resource\ResolvingResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

interface AclInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void;

    /**
     * @param \Spotman\Acl\ResourceInterface|string      $resource
     * @param \Spotman\Acl\ResourceInterface|string|null $parentResource
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function addResource(string $resource, ?string $parentResource = null): AclInterface;

    /**
     * @param string $identity
     *
     * @return \Spotman\Acl\ResourceInterface
     */
    public function getResource(string $identity): ResourceInterface;

    /**
     * @param string        $roleIdentity
     * @param string[]|null $parentRolesIdentities
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function addRole(string $roleIdentity, array $parentRolesIdentities = null): AclInterface;

    /**
     * @param string $roleIdentity
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function removeRole(string $roleIdentity): AclInterface;

    /**
     * @param string $roleIdentity
     *
     * @return bool
     */
    public function hasRole(string $roleIdentity): bool;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function addAllowRule(
        ?string $roleIdentity = null,
        ?string $resourceIdentity = null,
        ?string $permissionIdentity = null,
        ?string $bindToResourceIdentity = null
    ): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeAllowRule(
        ?string $roleIdentity = null,
        ?string $resourceIdentity = null,
        ?string $permissionIdentity = null,
        ?string $bindToResourceIdentity = null
    ): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function addDenyRule(
        ?string $roleIdentity = null,
        ?string $resourceIdentity = null,
        ?string $permissionIdentity = null,
        ?string $bindToResourceIdentity = null
    ): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeDenyRule(
        ?string $roleIdentity = null,
        ?string $resourceIdentity = null,
        ?string $permissionIdentity = null,
        ?string $bindToResourceIdentity = null
    ): void;

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string|null                    $permissionIdentity
     * @param \Spotman\Acl\AclRoleInterface  $role
     *
     * @return bool
     */
    public function isAllowedToRole(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclRoleInterface $role
    ): bool;

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\AclUserInterface  $user
     *
     * @return bool
     */
    public function isAllowedToUser(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclUserInterface $user
    ): bool;

    /**
     * Returns true if User has assigned role
     *
     * @param \Spotman\Acl\AclUserInterface            $user
     * @param \Zend\Permissions\Acl\Role\RoleInterface $role
     *
     * @return bool
     */
    public function hasAssignedRole(AclUserInterface $user, RoleInterface $role): bool;

    /**
     * Returns true if User has assigned any of provided roles
     *
     * @param \Spotman\Acl\AclUserInterface $user
     * @param RoleInterface[]               $roles
     *
     * @return bool
     */
    public function hasAnyAssignedRole(AclUserInterface $user, array $roles): bool;

    /**
     * Returns true if User has assigned Role
     *
     * @param \Spotman\Acl\AclUserInterface $user
     * @param string                        $roleId
     *
     * @return bool
     */
    public function hasAssignedRoleName(AclUserInterface $user, string $roleId): bool;

    /**
     * Returns true if User has assigned any of provided roles
     *
     * @param \Spotman\Acl\AclUserInterface $user
     * @param string[]                      $roles
     *
     * @return bool
     */
    public function hasAnyAssignedRoleName(AclUserInterface $user, array $roles): bool;

    /**
     * Inject UserAccessResolver into resource
     *
     * @param \Spotman\Acl\AclUserInterface                    $user
     * @param \Spotman\Acl\Resource\ResolvingResourceInterface $resource
     */
    public function injectUserResolver(AclUserInterface $user, ResolvingResourceInterface $resource): void;
}
