<?php
namespace Spotman\Acl\ResourceFactory;

use Spotman\Acl\ResourceInterface;

interface ResourceFactoryInterface
{
    /**
     * @param string            $identity
     *
     * @return ResourceInterface
     */
    public function createResource($identity);
}
