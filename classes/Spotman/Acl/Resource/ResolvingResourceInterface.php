<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\ResourceInterface;

interface ResolvingResourceInterface extends ResourceInterface
{
    /**
     * @return \Spotman\Acl\Resolver\AccessResolverInterface
     */
    public function getResolver();
}
