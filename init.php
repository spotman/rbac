<?php

use \Spotman\Acl\Acl;
use \Spotman\Acl\Resolver\AccessResolverInterface;
use \Spotman\Acl\ResourceFactoryInterface;
use \Spotman\Acl\PermissionsCollectorInterface;

$di = \BetaKiller\DI\Container::instance();

// TODO Default DI definitions for these interfaces

$di->call(function(AccessResolverInterface $resolver, ResourceFactoryInterface $resourceFactory, PermissionsCollectorInterface $collector) {
    Acl::instance()
        ->addPermissionsCollector($collector)
        ->setResourceFactory($resourceFactory)
        ->setAccessResolver($resolver)
        ->init();
});
