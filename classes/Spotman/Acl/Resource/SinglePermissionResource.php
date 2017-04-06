<?php
namespace Spotman\Acl\Resource;

abstract class SinglePermissionResource extends AbstractResolvingResource
{
    const PERMISSION_IDENTITY = 'enabled';

    public function isEnabled()
    {
        return $this->isPermissionAllowed(self::PERMISSION_IDENTITY);
    }

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    final public function getAvailablePermissionIdentities()
    {
        return [
            self::PERMISSION_IDENTITY
        ];
    }
}
