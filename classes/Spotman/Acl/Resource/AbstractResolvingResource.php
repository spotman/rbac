<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\AclException;

abstract class AbstractResolvingResource extends AbstractResource implements ResolvingResourceInterface
{
    /**
     * @var AclAccessResolverInterface
     */
    private $resolver;

    /**
     * @param \Spotman\Acl\AccessResolver\AclAccessResolverInterface $resolver
     *
     * @return void
     */
    public function useResolver(AclAccessResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $permissionIdentity
     *
     * @return bool
     */
    public function isPermissionAllowed(string $permissionIdentity): bool
    {
        if (!$this->resolver) {
            throw new AclException('AccessResolver is missing, you must set it via useResolver() method');
        }

        return $this->resolver->isAllowed($this, $permissionIdentity);
    }
}
