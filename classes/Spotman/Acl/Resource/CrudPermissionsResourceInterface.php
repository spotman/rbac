<?php
namespace Spotman\Acl\Resource;

interface CrudPermissionsResourceInterface extends ResolvingResourceInterface
{
    const PERMISSION_UPDATE = 'update';
    const PERMISSION_READ   = 'read';
    const PERMISSION_DELETE = 'delete';
    const PERMISSION_CREATE = 'create';

    /**
     * @return bool
     */
    public function isCreateAllowed();

    /**
     * @return bool
     */
    public function isReadAllowed();

    /**
     * @return bool
     */
    public function isUpdateAllowed();

    /**
     * @return bool
     */
    public function isDeleteAllowed();
}
