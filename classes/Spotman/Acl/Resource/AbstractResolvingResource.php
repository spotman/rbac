<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Exception;
use Spotman\Acl\Resolver\AccessResolverInterface;

abstract class AbstractResolvingResource extends AbstractResource implements ResolvingResourceInterface
{
    /**
     * @var AccessResolverInterface
     */
    private $resolver;

    /**
     * @param \Spotman\Acl\Resolver\AccessResolverInterface $resolver
     *
     * @return $this
     */
    public function useResolver(AccessResolverInterface $resolver)
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
