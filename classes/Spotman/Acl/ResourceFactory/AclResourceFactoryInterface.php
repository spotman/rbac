<?php
namespace Spotman\Acl\ResourceFactory;

use Spotman\Acl\ResourceInterface;

interface AclResourceFactoryInterface
{
    /**
     * @param string $identity
     *
     * @return ResourceInterface
     */
    public function createResource(string $identity): ResourceInterface;
}
