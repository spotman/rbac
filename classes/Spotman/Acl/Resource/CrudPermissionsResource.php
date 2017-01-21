<?php
namespace Spotman\Acl\Resource;

abstract class CrudPermissionsResource extends MultiplePermissionsResource
{
    const PERMISSION_CREATE = 'create';
    const PERMISSION_READ   = 'read';
    const PERMISSION_UPDATE = 'update';
    const PERMISSION_DELETE = 'delete';

    /**
     * @return string[]
     */
    public function getAvailablePermissionsIdentities()
    {
        return [
            self::PERMISSION_CREATE,
            self::PERMISSION_READ,
            self::PERMISSION_UPDATE,
            self::PERMISSION_DELETE,
        ];
    }

    public function isCreateAllowed()
    {
        return $this->isAllowed(self::PERMISSION_CREATE);
    }

    public function isReadAllowed()
    {
        return $this->isAllowed(self::PERMISSION_READ);
    }

    public function isUpdateAllowed()
    {
        return $this->isAllowed(self::PERMISSION_UPDATE);
    }

    public function isDeleteAllowed()
    {
        return $this->isAllowed(self::PERMISSION_DELETE);
    }
}
