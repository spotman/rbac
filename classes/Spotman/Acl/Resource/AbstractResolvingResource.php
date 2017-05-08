<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Exception;
use Spotman\Acl\AccessResolver\AclAccessResolverInterface;

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
    public function isPermissionAllowed($permissionIdentity)
    {
        if (!$this->resolver) {
            throw new Exception('AccessResolver is missing, you must set it via useResolver() method');
        }

        return $this->resolver->isAllowed($this, $permissionIdentity);
    }
}
