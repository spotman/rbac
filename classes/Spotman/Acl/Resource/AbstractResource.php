<?php
namespace Spotman\Acl\Resource;

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

    /**
     * serialize() checks if your class has a function with the magic name __sleep.
     * If so, that function is executed prior to any serialization.
     * It can clean up the object and is supposed to return an array with the names of all variables of that object that should be serialized.
     * If the method doesn't return anything then NULL is serialized and E_NOTICE is issued.
     * The intended use of __sleep is to commit pending data or perform similar cleanup tasks.
     * Also, the function is useful if you have very large objects which do not need to be saved completely.
     *
     * @return array|NULL
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.sleep
     */
    function __sleep()
    {
        // Do not serialize any data - this class must be stateless
        return [];
    }
}
