<?php
namespace BetaKiller\RBAC;

interface EntityInterface // TODO entity tree
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param EntityInterface $parent
     * @return $this
     */
    public function setParent(EntityInterface $parent);

    /**
     * @return EntityInterface
     */
    public function getParent();
}
