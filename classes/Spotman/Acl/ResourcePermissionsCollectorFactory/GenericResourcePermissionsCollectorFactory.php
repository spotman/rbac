<?php
namespace Spotman\Acl\ResourcePermissionsCollectorFactory;

use BetaKiller\Factory\NamespaceBasedFactory;
use Spotman\Acl\ResourceInterface;
use Spotman\Acl\ResourcePermissionsCollector\ResourcePermissionsCollectorInterface;

class GenericResourcePermissionsCollectorFactory implements ResourcePermissionsCollectorFactoryInterface
{
    /**
     * @var \BetaKiller\Factory\NamespaceBasedFactory
     */
    private $factory;

    /**
     * GenericResourcePermissionsCollectorFactory constructor.
     *
     * @param \BetaKiller\Factory\NamespaceBasedFactory $factory
     */
    public function __construct(NamespaceBasedFactory $factory)
    {
        $this->factory = $factory
            ->setClassPrefixes('Acl', 'ResourcePermissionsCollector')
            ->setClassSuffix('ResourcePermissionsCollector')
            ->setExpectedInterface(ResourcePermissionsCollectorInterface::class);
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourcePermissionsCollector\ResourcePermissionsCollectorInterface
     */
    public function createCollector(ResourceInterface $resource)
    {
        $collectorName = $resource->getResourceId();

        return $this->factory->create($collectorName, [
            'resource' => $resource,
        ]);
    }
}
