<?php
namespace Spotman\Acl\AccessResolver;

use Spotman\Acl\ResourceInterface;

interface AclAccessResolverInterface
{
    /**
     * @param \Spotman\Acl\ResourceInterface $resource
     * @param                                $permissionIdentity
     *
     * @return bool
     */
    public function isAllowed(ResourceInterface $resource, $permissionIdentity);
}
