<?php

use Spotman\Acl\Acl;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\Initializer\InitializerInterface;
use Spotman\Acl\Initializer\GenericInitializer;
use Spotman\Acl\Resolver\AccessResolverInterface;
use Spotman\Acl\Resolver\UserAccessResolver;
use Spotman\Acl\ResourcesCollector\ResourcesCollectorInterface;
use Spotman\Acl\ResourcesCollector\EmptyResourcesCollector;
use Spotman\Acl\RolesCollector\RolesCollectorInterface;
use Spotman\Acl\RolesCollector\EmptyRolesCollector;
use Spotman\Acl\PermissionsCollector\PermissionsCollectorInterface;
use Spotman\Acl\PermissionsCollector\EmptyPermissionsCollector;
use Spotman\Acl\ResourceFactory\ResourceFactoryInterface;
use Spotman\Acl\ResourceFactory\GenericResourceFactory;
use Doctrine\Common\Cache\ArrayCache;

return [

    'definitions'       =>  [

        // Acl facade
        Acl::class => DI\factory(function() {
            return Acl::instance();
        })->scope(\DI\Scope::SINGLETON),

        // Current user
        AclUserInterface::class                 => DI\get('User'),

        // Cache (using Doctrine`s ArrayCache as default)
        'AclCache'                              => DI\get(ArrayCache::class),

        // Basic initializer for DI containers with autowiring
        InitializerInterface::class             => DI\object(GenericInitializer::class)->lazy(), // Using lazy initializer pattern

        // Resolving resources` allowance relatively to current user
        AccessResolverInterface::class          => DI\get(UserAccessResolver::class),

        // No roles by default
        RolesCollectorInterface::class          => DI\get(EmptyRolesCollector::class),

        // No resources by default
        ResourcesCollectorInterface::class      => DI\get(EmptyResourcesCollector::class),

        // No permissions by default
        PermissionsCollectorInterface::class    => DI\get(EmptyPermissionsCollector::class),

        // Simple factory without DI
        ResourceFactoryInterface::class         => DI\get(GenericResourceFactory::class),

    ],

];
