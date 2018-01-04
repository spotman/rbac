<?php
namespace Spotman\Acl\ResourceRulesCollectorFactory;

use BetaKiller\Factory\NamespaceBasedFactory;
use Spotman\Acl\ResourceInterface;
use Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface;

class GenericAclResourceRulesCollectorFactory implements AclResourceRulesCollectorFactoryInterface
{
    /**
     * @var \BetaKiller\Factory\NamespaceBasedFactory
     */
    private $factory;

    /**
     * GenericAclResourceRulesCollectorFactory constructor.
     *
     * @param \BetaKiller\Factory\NamespaceBasedFactory $factory
     */
    public function __construct(NamespaceBasedFactory $factory)
    {
        $this->factory = $factory
            ->setClassNamespaces('Acl', 'ResourceRulesCollector')
            ->setClassSuffix('ResourceRulesCollector')
            ->setExpectedInterface(ResourceRulesCollectorInterface::class);
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface
     */
    public function createCollector(ResourceInterface $resource)
    {
        $collectorName = $resource->getResourceId();

        return $this->factory->create($collectorName, [
            'resource' => $resource,
        ]);
    }
}
