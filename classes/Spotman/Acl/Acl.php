<?php
namespace Spotman\Acl;

use Spotman\Acl\Initializer\InitializerInterface;
use Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\RolesCollectorInterface;
use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\Resolver\AccessResolverInterface;
use Spotman\Acl\ResourceFactory\ResourceFactoryInterface;
use Doctrine\Common\Cache\CacheProvider;

class Acl
{
    private static $_instance;

    private $initialized = false;

    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    private $acl;

    /**
     * @var ResourcesCollectorInterface[]
     */
    private $resourcesCollectors;

    /**
     * @var RolesCollectorInterface[]
     */
    private $rolesCollectors;

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

    /**
     * @var InitializerInterface
     */
    private $initializer;

    /**
     * @var CacheProvider
     */
    private $cache;

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

    public function setInitializer(InitializerInterface $initializer)
    {
        $this->initializer = $initializer;
        return $this;
    }

    public function init()
    {
        if ($this->initialized)
            return;

        $this->initializer->init($this);

        // Load cached data
        $cachedData = $this->getCachedData();

        if ($cachedData) {
            $this->restoreFromCacheData($cachedData);
            $this->initialized = true;
            return;
        }

        $this->acl = new \Zend\Permissions\Acl\Acl();

        // Collect roles
        $this->collectRoles();

        // Collect resources
        $this->collectResources();

        // Run permissions collectors
        $this->collectPermissions();

        $this->putDataInCache();

        $this->initialized = true;
    }

    protected function checkForInit()
    {
        if (!$this->initialized) {
            $this->init();
        }
    }

    public function setCache(CacheProvider $cache)
    {
        $this->cache = $cache;
        $this->cache->setNamespace($this->getCacheNamespace());
    }

    public function addRolesCollector(RolesCollectorInterface $collector)
    {
        $this->rolesCollectors[] = $collector->setAcl($this);
        return $this;
    }

    public function addResourcesCollector(ResourcesCollectorInterface $collector)
    {
        $this->resourcesCollectors[] = $collector->setAcl($this);
        return $this;
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

    public function resourceFactory($identity)
    {
        return $this->resourceFactory->createResource($identity);
    }

    public function addRole(AclRoleInterface $role, $parentRoleIdentity = null)
    {
        $this->acl->addRole($role, $parentRoleIdentity);
        return $this;
    }

    public function removeRole($roleIdentity)
    {
        $this->acl->removeRole($roleIdentity);
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

    public function collectRoles()
    {
        // Collect all roles
        foreach ($this->rolesCollectors as $rolesCollector) {
            $rolesCollector->collectRoles();
        }
    }

    public function collectResources()
    {
        // Collect all resources
        foreach ($this->resourcesCollectors as $resourcesCollector) {
            $resourcesCollector->collectResources();
        }
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
     * @param \Spotman\Acl\AclRoleInterface  $role
     *
     * @return bool
     */
    public function isAllowedToRole(ResourceInterface $resource, $permissionIdentity, AclRoleInterface $role)
    {
        // Make lazy loading
        $this->checkForInit();

        return $this->acl->isAllowed($role, $resource, $permissionIdentity);
    }

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\AclUserInterface  $user
     *
     * @return bool
     */
    public function isAllowedToUser(ResourceInterface $resource, $permissionIdentity, AclUserInterface $user)
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
        // Make lazy loading
        $this->checkForInit();

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
    protected function getCachedData()
    {
        // Get data from cache
        return $this->cache && $this->cache->fetch($this->getCacheKey());
    }

    protected function putDataInCache()
    {
        if (!$this->cache) {
            return false;
        }

        // Serializing Acl instance
        $data = serialize($this->acl);

        // Store in cache
        return $this->cache->save($this->getCacheKey(), $data);
    }

    /**
     * @param string $data
     */
    protected function restoreFromCacheData($data)
    {
        $this->acl = unserialize($data);
    }

    protected function getCacheNamespace()
    {
        return 'acl';
    }

    protected function getCacheKey()
    {
        return 'acl';
    }
}
