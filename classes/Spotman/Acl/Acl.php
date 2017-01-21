<?php
namespace Spotman\Acl;

use Spotman\Acl\Resolver\AccessResolverInterface;

class Acl
{
    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    private $acl;

    /**
     * @var PermissionsCollectorInterface[]
     */
    private $permissionsCollectors;

    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var AccessResolverInterface
     */
    private $accessResolver;

    private static $_instance;

    /**
     * @return Acl
     */
    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }
    /**
     * Acl constructor.
     * Prevent direct call via *new*
     * Use Acl::instance() instead
     */
    protected function __construct() {}

    public function init()
    {
        // TODO Load cached data
        $cachedData = '';

        if ($cachedData) {
            $this->restoreFromCacheData($cachedData);
            return;
        }

        $this->acl = new \Zend\Permissions\Acl\Acl();

        // TODO Collect roles
        // TODO Collect resources

        // Run permissions collectors
        $this->collectPermissions();
    }

    public function addPermissionsCollector(PermissionsCollectorInterface $collector)
    {
        $this->permissionsCollectors[] = $collector->setAcl($this);
        return $this;
    }

    public function addResource(ResourceInterface $resource, $parentResourceIdentity = null)
    {
        $this->acl->addResource($resource, $parentResourceIdentity);
        return $this;
    }

    public function addRole(RoleInterface $role, $parentRoleIdentity = null)
    {
        $this->acl->addRole($role, $parentRoleIdentity);
        return $this;
    }

    public function addAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null)
    {
        $this->acl->allow($roleIdentity, $resourceIdentity, $permissionIdentity);
    }

    public function removeAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null)
    {
        $this->acl->removeAllow($roleIdentity, $resourceIdentity, $permissionIdentity);
    }

    public function addDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null)
    {
        $this->acl->deny($roleIdentity, $resourceIdentity, $permissionIdentity);
    }

    public function removeDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null)
    {
        $this->acl->removeDeny($roleIdentity, $resourceIdentity, $permissionIdentity);
    }

    public function collectPermissions()
    {
        // Collect all entities
        foreach ($this->permissionsCollectors as $permissionCollector) {
            $permissionCollector->collectPermissions();
        }
    }

    public function setResourceFactory(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
        return $this;
    }

    public function resourceFactory($identity)
    {
        return $this->resourceFactory->createResource($identity);
    }

    /**
     * @return string[]
     */
    public function getResourcesIdentities()
    {
        return $this->acl->getResources();
    }

    /**
     * @param $identity
     *
     * @return ResourceInterface|\Zend\Permissions\Acl\Resource\ResourceInterface
     */
    public function getResource($identity)
    {
        return $this->acl->getResource($identity);
    }

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\RoleInterface     $role
     *
     * @return bool
     */
    public function isAllowedToRole(ResourceInterface $resource, $permissionIdentity, RoleInterface $role)
    {
        return $this->acl->isAllowed($role, $resource, $permissionIdentity);
    }

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\UserInterface     $user
     *
     * @return bool
     */
    public function isAllowedToUser(ResourceInterface $resource, $permissionIdentity, UserInterface $user)
    {
        $userRoles = $user->getAccessControlRoles();

        foreach ($userRoles as $role) {
            if ($this->isAllowedToRole($resource, $permissionIdentity, $role)) {
                return true;
            }
        }

        return false;
    }

    public function getAccessResolver()
    {
        return $this->accessResolver;
    }

    public function setAccessResolver(AccessResolverInterface $resolver)
    {
        $this->accessResolver = $resolver;
        return $this;
    }

    /**
     * @return string
     */
    protected function getCacheData()
    {
        // Serializing Acl instance
        return serialize($this->acl);
    }

    /**
     * @param string $data
     */
    protected function restoreFromCacheData($data)
    {
        $this->acl = unserialize($data);
    }
}
