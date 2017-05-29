<?php
namespace Spotman\Acl;

use Psr\Log\LoggerAwareInterface;

interface AclInterface extends LoggerAwareInterface
{
    const DI_CACHE_OBJECT_KEY = 'AclCache';

    /**
     * @return \Spotman\Acl\AclUserInterface
     */
    public function getCurrentUser();

    /**
     * @param \Spotman\Acl\ResourceInterface|string      $resource
     * @param \Spotman\Acl\ResourceInterface|string|null $parentResource
     *
     * @return $this
     */
    public function addResource($resource, $parentResource = null);

    /**
     * @param string $identity
     *
     * @return \Spotman\Acl\ResourceInterface|\Zend\Permissions\Acl\Resource\ResourceInterface
     */
    public function getResource($identity);

    /**
     * @param string     $roleIdentity
     * @param string[]|null $parentRolesIdentities
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function addRole($roleIdentity, $parentRolesIdentities = null);

    /**
     * @param string $roleIdentity
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function removeRole($roleIdentity);

    /**
     * @param string $roleIdentity
     *
     * @return bool
     */
    public function hasRole($roleIdentity);

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function addAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null);

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null);

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function addDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null);

    /**
     * @param string|null $roleIdentity
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     * @param string|null $bindToResourceIdentity
     */
    public function removeDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null);

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string|null                    $permissionIdentity
     * @param \Spotman\Acl\AclRoleInterface  $role
     *
     * @return bool
     */
    public function isAllowedToRole(ResourceInterface $resource, $permissionIdentity, AclRoleInterface $role);

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\AclUserInterface  $user
     *
     * @return bool
     */
    public function isAllowedToUser(ResourceInterface $resource, $permissionIdentity, AclUserInterface $user);

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                               $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity);
}
