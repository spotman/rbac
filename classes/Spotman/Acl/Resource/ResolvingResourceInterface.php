<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Resolver\AccessResolverInterface;
use Spotman\Acl\ResourceInterface;

interface ResolvingResourceInterface extends ResourceInterface
{
    /**
     * @param \Spotman\Acl\Resolver\AccessResolverInterface $resolver
     *
     * @return $this
     */
    public function useResolver(AccessResolverInterface $resolver);
}
