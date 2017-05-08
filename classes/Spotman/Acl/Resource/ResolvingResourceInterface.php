<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\ResourceInterface;

interface ResolvingResourceInterface extends ResourceInterface
{
    /**
     * @param \Spotman\Acl\AccessResolver\AclAccessResolverInterface $resolver
     *
     * @return $this
     */
    public function useResolver(AclAccessResolverInterface $resolver);

    /**
     * @param string $permissionIdentity
     *
     * @return bool
     */
    public function isPermissionAllowed($permissionIdentity);
}
