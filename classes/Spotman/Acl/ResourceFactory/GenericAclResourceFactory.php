<?php
namespace Spotman\Acl\ResourceFactory;

use Spotman\Acl\ResourceInterface;
use Spotman\Acl\Exception;

class GenericAclResourceFactory implements AclResourceFactoryInterface
{
    /**
     * @param string $identity
     *
     * @return ResourceInterface
     * @throws \Spotman\Acl\Exception
     */
    public function createResource(string $identity): ResourceInterface
    {
        $ns = "\\Spotman\\Acl\\Resource\\";
        $className = $ns.ucfirst($identity).ResourceInterface::SUFFIX;

        if (!class_exists($className)) {
            throw new Exception('Class :name does not exists', [':name' => $className]);
        }

        return new $className;
    }
}
