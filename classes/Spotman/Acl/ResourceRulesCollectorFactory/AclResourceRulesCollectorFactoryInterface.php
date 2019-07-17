<?php
namespace Spotman\Acl\ResourceRulesCollectorFactory;

use Spotman\Acl\ResourceInterface;
use Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface;

interface AclResourceRulesCollectorFactoryInterface
{
    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourceRulesCollector\ResourceRulesCollectorInterface
     */
    public function createCollector(ResourceInterface $resource): ResourceRulesCollectorInterface;
}
