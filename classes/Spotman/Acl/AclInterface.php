<?php
namespace Spotman\Acl;

use Psr\Log\LoggerAwareInterface;

interface AclInterface extends LoggerAwareInterface
{
    /**
     * @return \Spotman\Acl\AclUserInterface
     */
    public function getCurrentUser(): AclUserInterface;

    /**
     * @param \Spotman\Acl\ResourceInterface|string      $resource
     * @param \Spotman\Acl\ResourceInterface|string|null $parentResource
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function addResource($resource, $parentResource = null): AclInterface;

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
    public function addAllowRule(?string $roleIdentity = null, ?string $resourceIdentity = null, ?string $permissionIdentity = null, ?string $bindToResourceIdentity = null): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeAllowRule(?string $roleIdentity = null, ?string $resourceIdentity = null, ?string $permissionIdentity = null, ?string $bindToResourceIdentity = null): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function addDenyRule(?string $roleIdentity = null, ?string $resourceIdentity = null, ?string $permissionIdentity = null, ?string $bindToResourceIdentity = null): void;

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeDenyRule(?string $roleIdentity = null, ?string $resourceIdentity = null, ?string $permissionIdentity = null, ?string $bindToResourceIdentity = null): void;

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string|null                    $permissionIdentity
     * @param \Spotman\Acl\AclRoleInterface  $role
     *
     * @return bool
     */
    public function isAllowedToRole(ResourceInterface $resource, string $permissionIdentity, AclRoleInterface $role): bool;

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\AclUserInterface  $user
     *
     * @return bool
     */
    public function isAllowedToUser(ResourceInterface $resource, string $permissionIdentity, AclUserInterface $user): bool;

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, string $permissionIdentity): bool;
}
