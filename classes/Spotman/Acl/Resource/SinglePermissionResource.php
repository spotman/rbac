<?php
namespace Spotman\Acl\Resource;

abstract class SinglePermissionResource extends ResolvingResource
{
    const PERMISSION_IDENTITY = 'enabled';

    public function isEnabled()
    {
        return $this->isAllowed(self::PERMISSION_IDENTITY);
    }

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    public function getAvailablePermissionsIdentities()
    {
        return [
            self::PERMISSION_IDENTITY
        ];
    }
}
