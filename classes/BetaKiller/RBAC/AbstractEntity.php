<?php
namespace BetaKiller\RBAC;


abstract class AbstractEntity implements EntityInterface
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * AbstractEntity constructor.
     *
     * @param \BetaKiller\RBAC\Processor $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    protected function isGranted($permissionName, RoleInterface $role)
    {
        $permissionName = $this->makeEntityPermissionName($permissionName);
        return $this->processor->isGranted($permissionName, $role);
    }

    protected function makeEntityPermissionName($permissionName)
    {
        return implode($this->processor->getPermissionNameDelimiter(), [$this->getName(), $permissionName]);
    }
}
