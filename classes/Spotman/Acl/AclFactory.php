<?php
namespace Spotman\Acl;

use BetaKiller\DI\Container;
use Psr\Log\LoggerInterface;
use Spotman\Acl\Initializer\InitializerInterface;
use Doctrine\Common\Cache\CacheProvider;

class AclFactory
{
    public static function getInstance()
    {
        return new self;
    }

    /**
     * @return \Spotman\Acl\Acl
     */
    public function createAcl()
    {
        $di = Container::getInstance();

        $params = [
            'cache' => $di->get(Acl::DI_CACHE_OBJECT_KEY)
        ];

        $acl = $di->call(function(InitializerInterface $initializer, AclUserInterface $user, CacheProvider $cache, LoggerInterface $logger) {
            $acl = new Acl($initializer, $user, $cache);
            $acl->setLogger($logger);
            return $acl;
        }, $params);

        return $acl;
    }
}
