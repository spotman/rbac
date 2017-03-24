<?php
namespace Spotman\Acl;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Spotman\Acl\Initializer\InitializerInterface;
use Spotman\Acl\Resource\ResolvingResourceInterface;
use Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\RolesCollectorInterface;
use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\ResourceFactory\ResourceFactoryInterface;
use Doctrine\Common\Cache\CacheProvider;

class Acl implements LoggerAwareInterface
{
    const DI_CACHE_OBJECT_KEY = 'AclCache';

    private $initialized = false;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    private $acl;

    /**
     * @var ResourcesCollectorInterface
     */
    private $resourcesCollector;

    /**
     * @var RolesCollectorInterface
     */
    private $rolesCollector;

    /**
     * @var PermissionsCollectorInterface
     */
    private $permissionsCollector;

    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var \Spotman\Acl\AclUserInterface
     */
    private $currentUser;

    /**
     * @var CacheProvider
     */
    private $cache;

    public function __construct(InitializerInterface $initializer, AclUserInterface $user, CacheProvider $cache)
    {
        // Fetch objects from initializer
        $this->rolesCollector       = $initializer->getRolesCollector();
        $this->resourceFactory      = $initializer->getResourceFactory();
        $this->resourcesCollector   = $initializer->getResourcesCollector();
        $this->permissionsCollector = $initializer->getPermissionsCollector();

        $this->currentUser  = $user;
        $this->cache        = $cache;

        $this->cache->setNamespace($this->getCacheNamespace());
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Must be called in factory after object creation coz there is circular dependencies Acl::init() => collect resources => AccessResolverInterface => Acl => Acl::init
     */
    private function init()
    {
        if ($this->initialized) {
            return;
        }

        // Try to load cached data first
        if ($cachedData = $this->getCachedData()) {
            $this->logger && $this->logger->debug('Loading Acl from cached data');
            $this->restoreFromCacheData($cachedData);
            return;
        }

        // Normal initialization if no cache data provided
        $this->acl = new \Zend\Permissions\Acl\Acl();

        // Collect roles
        $this->rolesCollector->collectRoles($this);

        // Collect resources
        $this->resourcesCollector->collectResources($this);

        // Collect permissions
        $this->permissionsCollector->collectPermissions($this);

        $this->putDataInCache();

        $this->initialized = true;
    }

    protected function getZendAcl()
    {
        // Make lazy initialization
        $this->init();

        return $this->acl;
    }

    /**
     * @return \Spotman\Acl\AclUserInterface
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface|string      $resource
     * @param \Spotman\Acl\ResourceInterface|string|null $parentResource
     *
     * @return $this
     */
    public function addResource($resource, $parentResource = null)
    {
        if (!($resource instanceof ResourceInterface)) {
            $resource = $this->resourceFactory->createResource($resource);
        }

        $this->acl->addResource($resource, $parentResource);
        return $this;
    }

    public function addRole($roleIdentity, $parentRolesIdentities = null)
    {
        $this->acl->addRole($roleIdentity, $parentRolesIdentities);
        return $this;
    }

    public function removeRole($roleIdentity)
    {
        $this->acl->removeRole($roleIdentity);
        return $this;
    }

    public function hasRole($roleIdentity)
    {
        return $this->acl->hasRole($roleIdentity);
    }

    public function addAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null)
    {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->allow($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

    public function removeAllowRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null)
    {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->removeAllow($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

    public function addDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null)
    {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->deny($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

    public function removeDenyRule($roleIdentity = null, $resourceIdentity = null, $permissionIdentity = null, $bindToResourceIdentity = null)
    {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->removeDeny($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
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
        $this->init();

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resource->getResourceId(), $permissionIdentity);

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

    /**
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     *
     * @return null|string
     */
    protected function makeCompoundPermissionIdentity($resourceIdentity = null,  $permissionIdentity = null)
    {
        if ($permissionIdentity === null || $resourceIdentity === null) {
            return $permissionIdentity;
        }

        return $resourceIdentity.'.'.$permissionIdentity;
    }

    /**
     * @return string|null
     */
    protected function getCachedData()
    {
        // Get data from cache
        return $this->cache ? $this->cache->fetch($this->getCacheKey()) : null;
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
        if (!is_string($data)) {
            throw new Exception('Cached data is not a string, :type given', [':type' => gettype($data)]);
        }

        $this->acl = unserialize($data);

        if (!($this->acl && $this->acl instanceof \Zend\Permissions\Acl\Acl)) {
            throw new Exception('Cached data is not an Acl instance, :type given', [':type' => gettype($this->acl)]);
        }

        // Restore dependencies inside of resources
        foreach ($this->acl->getResources() as $key) {
            /** @var \Spotman\Acl\ResourceInterface $resource */
            $resource = $this->acl->getResource($key);

            // Restore Acl dependency inside of AccessResolver
            if ($resource instanceof ResolvingResourceInterface) {
                $resource->getResolver()->setAcl($this);
            }
        }
    }

    protected function getCacheNamespace()
    {
        return 'acl';
    }

    protected function getCacheKey()
    {
        $userIdentity = $this->currentUser->getAccessControlIdentity() ?: 'guest';

        // Unique key for each user (more space usage but less collision)
        return 'acl-user-'.$userIdentity;
    }
}
