<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Resolver\AccessResolverInterface;

abstract class ResolvingResource extends AbstractResource implements ResolvingResourceInterface
{
    /**
     * @var AccessResolverInterface
     */
    private $resolver;

    /**
     * ResolvingResource constructor.
     *
     * @param \Spotman\Acl\Resolver\AccessResolverInterface $resolver
     */
    public function __construct(AccessResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        parent::__construct();
    }

    /**
     * @return \Spotman\Acl\Resolver\AccessResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param string $permissionIdentity
     *
     * @return bool
     */
    protected function isAllowed($permissionIdentity)
    {
        return $this->resolver->isAllowed($this, $permissionIdentity);
    }
}
