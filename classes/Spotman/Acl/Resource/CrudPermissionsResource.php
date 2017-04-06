<?php
namespace Spotman\Acl\Resource;

abstract class CrudPermissionsResource extends AbstractResolvingResource
{
    const PERMISSION_CREATE = 'create';
    const PERMISSION_READ   = 'read';
    const PERMISSION_UPDATE = 'update';
    const PERMISSION_DELETE = 'delete';

    public function isCreateAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_CREATE);
    }

    public function isReadAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_READ);
    }

    public function isUpdateAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_UPDATE);
    }

    public function isDeleteAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_DELETE);
    }
}
