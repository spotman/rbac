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
        return $this->getResolver()->isAllowed($this, $permissionIdentity);
    }

    protected function getResolver()
    {
        $resolver = $this->resolver ?: Acl::getInstance()->getAccessResolver();

        if (!$resolver) {
            throw new Exception('Resolver is not defined for resource :name', [':name' => $this->getResourceId()]);
        }

        return $resolver;
    }
}
