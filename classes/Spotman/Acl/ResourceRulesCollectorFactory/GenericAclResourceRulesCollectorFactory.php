<?php
namespace Spotman\Acl\ResourceRulesCollectorFactory;

use BetaKiller\Factory\NamespaceBasedFactoryBuilder;
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
     * @param \BetaKiller\Factory\NamespaceBasedFactoryBuilder $factoryBuilder
     */
    public function __construct(NamespaceBasedFactoryBuilder $factoryBuilder)
    {
        $this->factory = $factoryBuilder
            ->createFactory()
            ->setClassNamespaces('Acl', 'ResourceRulesCollector')
            ->setClassSuffix('ResourceRulesCollector')
            ->setExpectedInterface(ResourceRulesCollectorInterface::class);
    }

    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface
     * @throws \BetaKiller\Factory\FactoryException
     */
    public function createCollector(ResourceInterface $resource): ResourceRulesCollectorInterface
    {
        $collectorName = $resource->getResourceId();

        return $this->factory->create($collectorName);
    }
}
