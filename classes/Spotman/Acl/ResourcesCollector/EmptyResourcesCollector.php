<?php
namespace Spotman\Acl\ResourcesCollector;

class EmptyResourcesCollector extends AbstractResourcesCollector
{
    /**
     * Collect resources from external source and add them to acl via protected methods addResource / removeResource
     */
    public function collectResources()
    {
        // Empty
    }
}
