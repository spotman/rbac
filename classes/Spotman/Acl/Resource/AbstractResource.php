<?php
namespace Spotman\Acl\Resource;

use Spotman\Acl\Exception;
use Spotman\Acl\ResourceInterface;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var string
     */
    private $identity;

    public function __construct() {}

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

    /**
     * Returns true if permission is allowed
     *
     * @param string $permissionIdentity
     *
     * @return bool
     */
    abstract protected function isAllowed($permissionIdentity);

    /**
     * Returns list of available permission identities
     *
     * @return string[]
     */
    abstract public function getAvailablePermissionsIdentities();

    public function __sleep()
    {
        // Do not serialize any data - this class must be stateless
        return [];
    }

    public function __wakeup()
    {
        throw new Exception('This class can not be serialized, consider using DI and helper classes');
    }
}
