<?php
namespace Spotman\Acl\Resolver;

use Spotman\Acl\ResourceInterface;

interface AccessResolverInterface
{
    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param                                $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity);
}
