<?php


namespace BetaKiller\RBAC;


class PermissionsCache
{
    public function load()
    {
        // TODO Load from per-user storage
        return false;
    }

    public function isGranted(RoleInterface $role, $permissionName)
    {

    }

    public function save(EntityRegistry $entityRegistry)
    {
        return true;
    }
}
