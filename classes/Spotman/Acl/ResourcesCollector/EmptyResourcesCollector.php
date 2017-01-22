<?php
namespace Spotman\Acl\ResourcesCollector;

class EmptyResourcesCollector extends AbstractResourcesCollector
{
    /**
     * Collect roles from external source and add them to acl via protected methods addRole / removeRole
     */
    public function collectResources()
    {
        // Empty
    }
}
