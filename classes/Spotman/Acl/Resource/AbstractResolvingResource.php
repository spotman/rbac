<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\Exception;

abstract class AbstractResolvingResource extends AbstractResource implements ResolvingResourceInterface
{
    /**
     * @var AclAccessResolverInterface
     */
    private $resolver;

    /**
     * @param \Spotman\Acl\AccessResolver\AclAccessResolverInterface $resolver
     *
     * @return $this
     */
    public function useResolver(AclAccessResolverInterface $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @param string $permissionIdentity
     *
     * @return bool
     */
    public function isPermissionAllowed(string $permissionIdentity): bool
    {
        if (!$this->resolver) {
            throw new Exception('AccessResolver is missing, you must set it via useResolver() method');
        }

        return $this->resolver->isAllowed($this, $permissionIdentity);
    }
}
