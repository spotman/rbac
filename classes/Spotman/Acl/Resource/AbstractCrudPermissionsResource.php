<?php
namespace Spotman\Acl\Resource;

abstract class AbstractCrudPermissionsResource extends AbstractResolvingResource implements CrudPermissionsResourceInterface
{
    /**
     * @return bool
     */
    public function isCreateAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_CREATE);
    }

    /**
     * @return bool
     */
    public function isReadAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_READ);
    }

    /**
     * @return bool
     */
    public function isUpdateAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_UPDATE);
    }

    /**
     * @return bool
     */
    public function isDeleteAllowed()
    {
        return $this->isPermissionAllowed(self::PERMISSION_DELETE);
    }
}
