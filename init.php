<?php

use Spotman\Acl\Acl;
use Spotman\Acl\Initializer\InitializerInterface;
use Doctrine\Common\Cache\CacheProvider;

$acl_autoload_token = \Profiler::start('Acl', 'autoload');

$di = \BetaKiller\DI\Container::instance();

$di->call(function(Acl $acl, InitializerInterface $initializer, CacheProvider $cache) use ($acl_autoload_token) {
    \Profiler::stop($acl_autoload_token);

    $acl_init_token = \Profiler::start('Acl', 'construct');

    $acl->setCache($cache);
    $acl->setInitializer($initializer);

    \Profiler::stop($acl_init_token);
}, ['cache' => $di->get('AclCache')]);

unset($acl_init_token, $acl_autoload_token);
