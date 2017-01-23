<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Acl;
use Spotman\Acl\Exception;
use Spotman\Acl\Resolver\AccessResolverInterface;

abstract class ResolvingResource extends AbstractResource implements ResolvingResourceInterface
{
    /**
     * @var AccessResolverInterface
     */
    private $resolver;

    public function useResolver(AccessResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    protected function isAllowed($permissionIdentity)
    {
        // Get default Acl resolver if none provided
        $resolver = $this->resolver ?: Acl::instance()->getAccessResolver();

        if (!$resolver) {
            throw new Exception('Resolver is not defined for resource :name', [':name' => $this->getResourceId()]);
        }

        return $resolver->isAllowed($this, $permissionIdentity);
    }
}
