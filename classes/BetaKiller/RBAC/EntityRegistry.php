<?php
namespace BetaKiller\RBAC;

class EntityRegistry
{
    /**
     * @var EntityInterface[]
     */
    private $registry;

    /**
     * @param string $name
     *
     * @return \BetaKiller\RBAC\EntityInterface
     * @throws \BetaKiller\RBAC\Exception
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new Exception('No entity with name [:name]', [':name' => $name]);
        }

        return $this->registry[$name];
    }

    public function has($name)
    {
        return isset($this->registry[$name]);
    }

    public function set(EntityInterface $entity)
    {
        $name = $entity->getName();

        if ($this->has($name)) {
            throw new Exception('Duplicate entity with name [:name]', [':name' => $name]);
        }

        $this->registry[$name] = $entity;
    }
}
