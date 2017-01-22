<?php
namespace Spotman\Acl\ResourcesCollector;

use Spotman\Acl\Acl;

/**
 * Class AbstractRolesCollector
 *
 * @package Spotman\Acl
 */
abstract class AbstractResourcesCollector implements ResourcesCollectorInterface
{
    /**
     * @var Acl
     */
    private $acl;

    /**
     * AbstractRolesCollector constructor.
     */
    public function __construct() {}

    /**
     * @param \Spotman\Acl\Acl $acl
     *
     * @return $this
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }

    protected function addResource($identity, $parentResourceIdentity = null)
    {
        $resource = $this->acl->resourceFactory($identity);
        $this->acl->addResource($resource, $parentResourceIdentity);
    }
}
