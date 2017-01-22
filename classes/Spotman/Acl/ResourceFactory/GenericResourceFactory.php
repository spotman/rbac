<?php
namespace Spotman\Acl\ResourceFactory;

use Spotman\Acl\ResourceInterface;
use Spotman\Acl\Exception;

class GenericResourceFactory implements ResourceFactoryInterface
{
    /**
     * @param string $identity
     *
     * @return ResourceInterface
     * @throws \Spotman\Acl\Exception
     */
    public function createResource($identity)
    {
        $ns = "\\Spotman\\Acl\\Resource\\";
        $className = $ns.ucfirst($identity).'Resource';

        if (!class_exists($className)) {
            throw new Exception('Class :name does not exists', [':name' => $className]);
        }

        return new $className;
    }
}
