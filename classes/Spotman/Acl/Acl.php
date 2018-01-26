<?php
namespace Spotman\Acl;

use Doctrine\Common\Cache\Cache;
use Psr\Log\LoggerInterface;
use Spotman\Acl\Initializer\AclInitializerInterface;
use Spotman\Acl\Resource\ResolvingResourceInterface;
use Spotman\Acl\ResourceFactory\AclResourceFactoryInterface;
use Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\AclRolesCollectorInterface;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;

class Acl implements AclInterface
{
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
     * @var AclResourcesCollectorInterface
     */
    private $resourcesCollector;

    /**
     * @var AclRolesCollectorInterface
     */
    private $rolesCollector;

    /**
     * @var AclRulesCollectorInterface
     */
    private $permissionsCollector;

    /**
     * @var \Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface
     */
    private $rulesCollectorFactory;

    /**
     * @var AclResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var \Spotman\Acl\AccessResolver\AclAccessResolverInterface
     */
    private $accessResolver;

    /**
     * @var \Spotman\Acl\AclUserInterface
     */
    private $currentUser;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * Acl constructor.
     *
     * @param \Spotman\Acl\Initializer\AclInitializerInterface $initializer
     * @param \Spotman\Acl\AclUserInterface                    $user
     * @param \Doctrine\Common\Cache\Cache                     $cache
     *
     * @throws \Spotman\Acl\Exception
     */
    public function __construct(AclInitializerInterface $initializer, AclUserInterface $user, Cache $cache)
    {
        // Fetch objects from initializer
        $this->accessResolver        = $initializer->getDefaultAccessResolver();
        $this->rolesCollector        = $initializer->getRolesCollector();
        $this->resourceFactory       = $initializer->getResourceFactory();
        $this->resourcesCollector    = $initializer->getResourcesCollector();
        $this->permissionsCollector  = $initializer->getPermissionsCollector();
        $this->rulesCollectorFactory = $initializer->getResourceRulesCollectorFactory();

        $this->currentUser = $user;
        $this->cache       = $cache;

        $this->init();
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Must be called in factory after object creation coz there is circular dependencies Acl::init() => collect resources => AclAccessResolverInterface => Acl => Acl::init
     *
     * @throws \Spotman\Acl\Exception
     */
    private function init(): void
    {
        if ($this->initialized) {
            return;
        }

        // Try to load cached data first
        if ($this->restoreFromCacheData()) {
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

    /**
     * @return \Spotman\Acl\AclUserInterface
     */
    public function getCurrentUser(): AclUserInterface
    {
        return $this->currentUser;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface|string      $resource
     * @param \Spotman\Acl\ResourceInterface|string|null $parentResource
     *
     * @return \Spotman\Acl\AclInterface
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function addResource($resource, $parentResource = null): AclInterface
    {
        if (!($resource instanceof ResourceInterface)) {
            $resource = $this->resourceFactory->createResource($resource);
        }

        if ($parentResource && !($parentResource instanceof ResourceInterface)) {
            $parentResource = $this->resourceFactory->createResource($parentResource);
        }

        $this->acl->addResource($resource, $parentResource);

        // Add default permissions
        $this->importResourceDefaultPermissions($resource);

        // Use custom permissions collector for current resource
        if ($resource->isCustomRulesCollectorUsed()) {
            /** @var AclRulesCollectorInterface $collectorInstance */
            $collectorInstance = $this->rulesCollectorFactory->createCollector($resource);

            $collectorInstance->collectPermissions($this);
        }

        return $this;
    }

    private function importResourceDefaultPermissions(ResourceInterface $resource): void
    {
        foreach ($resource->getDefaultAccessList() as $permissionIdentity => $roles) {
            /** @var string[] $roles */
            foreach ($roles as $role) {
                $this->addAllowRule($role, $resource->getResourceId(), $permissionIdentity);
            }
        }
    }

    /**
     * @param string $identity
     *
     * @return \Spotman\Acl\ResourceInterface
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     * @throws \Spotman\Acl\Exception
     */
    public function getResource(string $identity): ResourceInterface
    {
        if (!$this->acl->hasResource($identity)) {
            $resource = $this->resourceFactory->createResource($identity);
            $this->addResource($resource);
        } else {
            $resource = $this->acl->getResource($identity);
        }

        if (!($resource instanceof ResourceInterface)) {
            throw new Exception('Resource :name must implement :must', [
                ':name' => $identity,
                ':must' => ResourceInterface::class,
            ]);
        }

        if ($resource instanceof ResolvingResourceInterface) {
            // Inject AccessResolver in restored resources
            $resource->useResolver($this->accessResolver);
        }

        return $resource;
    }

    /**
     * @param string     $roleIdentity
     * @param array|null $parentRolesIdentities
     *
     * @return \Spotman\Acl\AclInterface
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function addRole(string $roleIdentity, array $parentRolesIdentities = null): AclInterface
    {
        $this->acl->addRole($roleIdentity, $parentRolesIdentities);

        return $this;
    }

    public function removeRole(string $roleIdentity): AclInterface
    {
        $this->acl->removeRole($roleIdentity);

        return $this;
    }

    public function hasRole(string $roleIdentity): bool
    {
        return $this->acl->hasRole($roleIdentity);
    }

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
    ): void {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->allow($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

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
    ): void {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->removeAllow($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

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
    ): void {
        if (!$bindToResourceIdentity) {
            $bindToResourceIdentity = $resourceIdentity;
        }

        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->deny($roleIdentity, $bindToResourceIdentity, $permissionIdentity);
    }

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
    ): void {
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
     * @param string|null                    $permissionIdentity
     * @param \Spotman\Acl\AclRoleInterface  $role
     *
     * @return bool
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function isAllowedToRole(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclRoleInterface $role
    ): bool {
        // Add missing resource and import it's default permissions
        if (!$this->acl->hasResource($resource)) {
            $this->addResource($resource);
        }

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
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function isAllowedToUser(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclUserInterface $user
    ): bool {
        $userRoles = $user->getAccessControlRoles();

        foreach ($userRoles as $role) {
            if ($this->isAllowedToRole($resource, $permissionIdentity, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     *
     * @return bool
     * @throws \Zend\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function isAllowed(ResourceInterface $resource, string $permissionIdentity): bool
    {
        return $this->isAllowedToUser($resource, $permissionIdentity, $this->currentUser);
    }

    /**
     * @param string|null $resourceIdentity
     * @param string|null $permissionIdentity
     *
     * @return null|string
     */
    protected function makeCompoundPermissionIdentity($resourceIdentity = null, $permissionIdentity = null): ?string
    {
        if ($permissionIdentity === null || $resourceIdentity === null) {
            return $permissionIdentity;
        }

        return $resourceIdentity.'.'.$permissionIdentity;
    }

    /**
     * @return string|null
     */
    protected function getCachedData(): ?string
    {
        // Get data from cache
        return $this->cache->fetch($this->getCacheKey());
    }

    protected function putDataInCache(): bool
    {
        // Serializing Acl instance
        $data = serialize($this->acl);

        // Store in cache
        return $this->cache->save($this->getCacheKey(), $data);
    }

    /**
     * @throws \Spotman\Acl\Exception
     */
    protected function restoreFromCacheData(): bool
    {
        $data = $this->getCachedData();

        if (!$data) {
            return false;
        }

        if (!\is_string($data)) {
            throw new Exception('Cached data is not a string, :type given', [':type' => \gettype($data)]);
        }

        $this->logger && $this->logger->debug('Loading Acl from cached data');

        $this->acl = unserialize($data, [
            \Zend\Permissions\Acl\Acl::class,
            ResourceInterface::class,
        ]);

        if (!($this->acl && $this->acl instanceof \Zend\Permissions\Acl\Acl)) {
            throw new Exception('Cached data is not an Acl instance, :type given', [':type' => \gettype($this->acl)]);
        }

        return true;
    }

    protected function getCacheKey(): string
    {
        $userIdentity = $this->currentUser->getAccessControlIdentity() ?: 'guest';

        // Unique key for each user (more space usage but no collisions)
        return 'acl.user-'.$userIdentity;
    }
}
