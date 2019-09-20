<?php
namespace Spotman\Acl\Resource;

abstract class SinglePermissionResource extends AbstractResolvingResource
{
    protected const PERMISSION_IDENTITY = 'enabled';

    public function isEnabled(): bool
    {
        return $this->isPermissionAllowed(self::PERMISSION_IDENTITY);
    }

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    final public function getAvailablePermissionIdentities(): array
    {
        return [
            self::PERMISSION_IDENTITY
        ];
    }
}
