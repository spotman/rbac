<?php
namespace Spotman\Acl;

interface ResourceFactoryInterface
{
    /**
     * @param string            $identity
     *
     * @return ResourceInterface
     */
    public function createResource($identity);
}
