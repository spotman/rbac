<?php
namespace Spotman\Acl\Resolver;

use Spotman\Acl\Acl;
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

    /**
     * Stores Acl instance after unserialize
     *
     * @param \Spotman\Acl\Acl $acl
     */
    public function setAcl(Acl $acl);
}
