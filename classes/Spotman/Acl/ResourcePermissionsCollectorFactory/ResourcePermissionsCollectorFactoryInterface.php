<?php
namespace Spotman\Acl\ResourcePermissionsCollectorFactory;

use Spotman\Acl\ResourceInterface;

interface ResourcePermissionsCollectorFactoryInterface
{
    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     *
     * @return \Spotman\Acl\ResourcePermissionsCollector\ResourcePermissionsCollectorInterface
     */
    public function createCollector(ResourceInterface $resource);
}
