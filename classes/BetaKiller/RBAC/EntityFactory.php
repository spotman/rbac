<?php
namespace BetaKiller\RBAC;


abstract class EntityFactory
{
    abstract public function createInstance(Processor $processor, $name, array $permissions);
}
