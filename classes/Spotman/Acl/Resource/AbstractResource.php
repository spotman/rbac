<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\ResourceInterface;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var string
     */
    private $identity;

    /**
     * AbstractResource constructor.
     *
     * This object must be lightweight and has no external dependencies
     * All operations must be done via Flyweight or Visitor patterns
     */
    final public function __construct() {}

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        if (!$this->identity) {
            $this->identity = $this->detectIdentity();
        }

        return $this->identity;
    }

    protected function detectIdentity()
    {
        $className = static::class;
        $pos = strrpos($className, '\\');
        $baseName = substr($className, $pos + 1);
        return str_replace('Resource', '', $baseName);
    }

    public function __sleep()
    {
        // Do not serialize any data - this class must be stateless
        return [];
    }
}
