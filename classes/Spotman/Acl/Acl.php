<?php
namespace Spotman\Acl;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Spotman\Acl\AccessResolver\UserAccessResolver;
use Spotman\Acl\Initializer\AclInitializerInterface;
use Spotman\Acl\Resource\ResolvingResourceInterface;
use Spotman\Acl\ResourceFactory\AclResourceFactoryInterface;
use Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface;
use Spotman\Acl\RolesCollector\AclRolesCollectorInterface;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;
use Laminas\Permissions\Acl\Acl as LaminasAcl;
use Laminas\Permissions\Acl\Role\RoleInterface;

class Acl implements AclInterface
{
    private bool $initialized = false;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /**
     * @var \Laminas\Permissions\Acl\Acl
     */
    private LaminasAcl $acl;

    /**
     * @var AclResourcesCollectorInterface
     */
    private AclResourcesCollectorInterface $resourcesCollector;

    /**
     * @var AclRolesCollectorInterface
     */
    private AclRolesCollectorInterface $rolesCollector;

    /**
     * @var AclRulesCollectorInterface
     */
    private AclRulesCollectorInterface $permissionsCollector;

    /**
     * @var \Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface
     */
    private ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface $rulesCollectorFactory;

    /**
     * @var AclResourceFactoryInterface
     */
    private AclResourceFactoryInterface $resourceFactory;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private CacheInterface $cache;

    /**
     * Acl constructor.
     *
     * @param \Spotman\Acl\Initializer\AclInitializerInterface $initializer
     * @param \Psr\SimpleCache\CacheInterface                  $cache
     *
     * @throws \Spotman\Acl\AclException
     */
    public function __construct(AclInitializerInterface $initializer, CacheInterface $cache)
    {
        // Fetch objects from initializer
        $this->rolesCollector        = $initializer->getRolesCollector();
        $this->resourceFactory       = $initializer->getResourceFactory();
        $this->resourcesCollector    = $initializer->getResourcesCollector();
        $this->permissionsCollector  = $initializer->getPermissionsCollector();
        $this->rulesCollectorFactory = $initializer->getResourceRulesCollectorFactory();

        $this->cache = $cache;

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
     * @throws \Spotman\Acl\AclException
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
        $this->acl = new LaminasAcl();

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
     * @param string      $resourceIdentity
     * @param null|string $parentIdentity
     *
     * @return \Spotman\Acl\AclInterface
     */
    public function addResource(string $resourceIdentity, ?string $parentIdentity = null): AclInterface
    {
        // Check resource and its parent has dedicated classes
        $resource = $this->resourceFactory->createResource($resourceIdentity);

        $parent = $parentIdentity
            ? $this->resourceFactory->createResource($parentIdentity)
            : null;

        // Add plain text resource for memory saving
        $this->acl->addResource($resource->getResourceId(), $parent ? $parent->getResourceId() : null);

        // Import default permissions
        $this->importResourceDefaultPermissions($resource);

        // Use custom permissions collector for current resource
        if ($resource->isCustomRulesCollectorUsed()) {
            $collectorInstance = $this->rulesCollectorFactory->createCollector($resource);

            $collectorInstance->collectResourcePermissions($resource, $this);
        }

        return $this;
    }

    private function importResourceDefaultPermissions(ResourceInterface $resource): void
    {
        foreach ($resource->getDefaultAccessList() as $permissionIdentity => $roles) {
            foreach ($roles as $role) {
                $this->addAllowRule($role, $resource->getResourceId(), $permissionIdentity);
            }
        }
    }

    /**
     * @param string $identity
     *
     * @return \Spotman\Acl\ResourceInterface
     * @throws \Laminas\Permissions\Acl\Exception\InvalidArgumentException
     * @throws \Spotman\Acl\AclException
     */
    public function getResource(string $identity): ResourceInterface
    {
        if (!$this->acl->hasResource($identity)) {
            $this->addResource($identity);
        }

        return $this->createResource($identity);
    }

    /**
     * @param string     $roleIdentity
     * @param array|null $parentRolesIdentities
     *
     * @return \Spotman\Acl\AclInterface
     * @throws \Laminas\Permissions\Acl\Exception\InvalidArgumentException
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

//        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->allow($roleIdentity, $bindToResourceIdentity, $permissionIdentity);

        $this->logger && $this->logger->debug(':resource.:permission is allowed to ":role"', [
            ':resource'   => $resourceIdentity ?? 'any',
            ':permission' => $permissionIdentity ?? 'any',
            ':role'       => $roleIdentity ?? 'any',
        ]);
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

//        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

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

//        $permissionIdentity = $this->makeCompoundPermissionIdentity($resourceIdentity, $permissionIdentity);

        $this->acl->deny($roleIdentity, $bindToResourceIdentity, $permissionIdentity);

        $this->logger && $this->logger->debug(':resource.:permission is denied to ":role"', [
            ':resource'   => $resourceIdentity,
            ':permission' => $permissionIdentity,
            ':role'       => $roleIdentity,
        ]);
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
    public function isAllowedToRole(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclRoleInterface $role
    ): bool {
        $resourceIdentity = $resource->getResourceId();

        // Add missing resource and import it's default permissions
        if (!$this->acl->hasResource($resourceIdentity)) {
            $this->addResource($resourceIdentity);
        }

//        $permissionIdentity = $this->makeCompoundPermissionIdentity($resource->getResourceId(), $permissionIdentity);

        $result = $this->acl->isAllowed($role, $resource, $permissionIdentity);

        $this->logger && $this->logger->debug(':result :resource.:permission to ":role"', [
            ':resource'   => $resourceIdentity,
            ':permission' => $permissionIdentity,
            ':role'       => $role->getRoleId(),
            ':result'     => $result ? 'ALLOWED' : 'DENIED',
        ]);

        return $result;
    }

    /**
     * Check for raw permission name
     *
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param string                         $permissionIdentity
     * @param \Spotman\Acl\AclUserInterface  $user
     *
     * @return bool
     * @throws \Laminas\Permissions\Acl\Exception\InvalidArgumentException
     */
    public function isAllowedToUser(
        ResourceInterface $resource,
        string $permissionIdentity,
        AclUserInterface $user
    ): bool {
        foreach ($user->getAccessControlRoles() as $role) {
            if ($this->isAllowedToRole($resource, $permissionIdentity, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasAssignedRole(AclUserInterface $user, RoleInterface $role): bool
    {
        foreach ($user->getAccessControlRoles() as $userRole) {
            if ($userRole->getRoleId() === $role->getRoleId()) {
                return true;
            }

            if ($this->acl->inheritsRole($userRole, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasAnyAssignedRole(AclUserInterface $user, array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasAssignedRole($user, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasAssignedRoleName(AclUserInterface $user, string $roleId): bool
    {
        $role = $this->acl->getRole($roleId);

        return $this->hasAssignedRole($user, $role);
    }

    /**
     * @inheritDoc
     */
    public function hasAnyAssignedRoleName(AclUserInterface $user, array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasAssignedRoleName($user, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Inject UserAccessResolver into resource
     *
     * @param \Spotman\Acl\AclUserInterface                    $user
     * @param \Spotman\Acl\Resource\ResolvingResourceInterface $resource
     */
    public function injectUserResolver(AclUserInterface $user, ResolvingResourceInterface $resource): void
    {
        $resolver = new UserAccessResolver($this, $user);

        $resource->useResolver($resolver);
    }

    private function createResource(string $identity): ResourceInterface
    {
        $resource = $this->resourceFactory->createResource($identity);

        if (!($resource instanceof ResourceInterface)) {
            throw new AclException('Resource :name must implement :must', [
                ':name' => $identity,
                ':must' => ResourceInterface::class,
            ]);
        }

        return $resource;
    }

    /**
     * @return string|null
     */
    protected function getCachedData(): ?string
    {
        // Get data from cache
        return $this->cache->get($this->getCacheKey());
    }

    protected function putDataInCache(): bool
    {
        // Serializing Acl instance
        $data = serialize($this->acl);

        // Store in cache
        return $this->cache->set($this->getCacheKey(), $data);
    }

    /**
     * @throws \Spotman\Acl\AclException
     */
    protected function restoreFromCacheData(): bool
    {
        $data = $this->getCachedData();

        if (!$data) {
            return false;
        }

        if (!\is_string($data)) {
            throw new AclException('Cached data is not a string, :type given', [':type' => \gettype($data)]);
        }

        $this->logger && $this->logger->debug('Loading Acl from cached data');

        $obj = unserialize($data, [
            LaminasAcl::class,
        ]);

        if (!($obj instanceof LaminasAcl)) {
            throw new AclException('Cached data is not an Acl instance, :type given', [
                ':type' => \gettype($this->acl),
            ]);
        }

        $this->acl = $obj;

        return true;
    }

    protected function getCacheKey(): string
    {
        return 'acl.cache';
    }
}
