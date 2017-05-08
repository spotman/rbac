<?php

use Spotman\Acl\Acl;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\Initializer\InitializerInterface;
use Spotman\Acl\Initializer\GenericInitializer;
use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\AccessResolver\UserAccessResolver;
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
            return \Spotman\Acl\AclFactory::getInstance()->createAcl();
        })->scope(\DI\Scope::SINGLETON),

        // Current user
        AclUserInterface::class              => DI\get('User'),

        // Cache (using Doctrine`s ArrayCache as default)
        Acl::DI_CACHE_OBJECT_KEY             => DI\object(ArrayCache::class),

        // Basic initializer for DI containers with autowiring
        InitializerInterface::class          => DI\object(GenericInitializer::class)->lazy(), // Using lazy initializer pattern

        // Resolving resources` access relatively to current user
        AclAccessResolverInterface::class    => DI\object(UserAccessResolver::class),

        // No roles by default
        RolesCollectorInterface::class       => DI\object(EmptyRolesCollector::class),

        // No resources by default
        ResourcesCollectorInterface::class   => DI\object(EmptyResourcesCollector::class),

        // No permissions by default
        PermissionsCollectorInterface::class => DI\object(EmptyPermissionsCollector::class),

        // Simple factory without DI
        ResourceFactoryInterface::class      => DI\object(GenericResourceFactory::class),

    ],

];
