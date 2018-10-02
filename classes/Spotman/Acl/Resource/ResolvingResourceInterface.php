<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\ResourceInterface;

interface ResolvingResourceInterface extends ResourceInterface
{
    /**
     * @param \Spotman\Acl\AccessResolver\AclAccessResolverInterface $resolver
     *
     * @return void
     */
    public function useResolver(AclAccessResolverInterface $resolver): void;

    /**
     * @param string $permissionIdentity
     *
     * @return bool
     */
    public function isPermissionAllowed(string $permissionIdentity): bool;
}
