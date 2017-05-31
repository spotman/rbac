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

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    public function getAvailablePermissionIdentities()
    {
        return array_keys($this->getDefaultAccessList());
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

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return $this->getResourceId();
    }

    /**
     * Returns true if this resource needs custom permission collector
     *
     * @return bool
     */
    public function isCustomRulesCollectorUsed()
    {
        // False by default
        return false;
    }

    /**
     * Returns true if permission was previously defined
     *
     * @param string $name
     *
     * @return bool
     */
    protected function isPermissionDefined($name)
    {
        $default = $this->getDefaultAccessList();

        return isset($default[$name]);
    }
}
