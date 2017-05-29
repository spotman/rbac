<?php
namespace Spotman\Acl\ResourceRulesCollectorFactory;

use Spotman\Acl\ResourceInterface;

interface AclResourceRulesCollectorFactoryInterface
{
    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface
     */
    public function createCollector(ResourceInterface $resource);
}
