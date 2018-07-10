<?php

use Spotman\Acl\AccessResolver\AclAccessResolverInterface;
use Spotman\Acl\AccessResolver\UserAccessResolver;
use Spotman\Acl\Acl;
use Spotman\Acl\AclInterface;
use Spotman\Acl\AclUserInterface;
use Spotman\Acl\Initializer\AclInitializerInterface;
use Spotman\Acl\Initializer\GenericAclInitializer;
use Spotman\Acl\ResourceFactory\AclResourceFactoryInterface;
use Spotman\Acl\ResourceFactory\GenericAclResourceFactory;
use Spotman\Acl\ResourceRulesCollectorFactory\AclResourceRulesCollectorFactoryInterface;
use Spotman\Acl\ResourceRulesCollectorFactory\GenericAclResourceRulesCollectorFactory;
use Spotman\Acl\ResourcesCollector\AclResourcesCollectorInterface;
use Spotman\Acl\ResourcesCollector\EmptyAclResourcesCollector;
use Spotman\Acl\RolesCollector\AclRolesCollectorInterface;
use Spotman\Acl\RolesCollector\EmptyAclRolesCollector;
use Spotman\Acl\RulesCollector\AclRulesCollectorInterface;
use Spotman\Acl\RulesCollector\EmptyAclRulesCollector;

return [

    'definitions' => [

        // Lazy Acl binding (prevents circular dependencies)
        AclInterface::class                              => DI\autowire(Acl::class)->lazy(),

        // Current user
        AclUserInterface::class                          => DI\get('User'),

        // Basic initializer for DI containers with autowiring ("Lazy initialization" pattern)
        AclInitializerInterface::class                   => DI\autowire(GenericAclInitializer::class)->lazy(),

        // Resolving resources` access relatively to current user
        AclAccessResolverInterface::class                => DI\autowire(UserAccessResolver::class)->lazy(),

        // No roles by default
        AclRolesCollectorInterface::class                => DI\autowire(EmptyAclRolesCollector::class),

        // No resources by default
        AclResourcesCollectorInterface::class            => DI\autowire(EmptyAclResourcesCollector::class),

        // No permissions by default
        AclRulesCollectorInterface::class                => DI\autowire(EmptyAclRulesCollector::class),

        // Simple factory without DI
        AclResourceFactoryInterface::class               => DI\autowire(GenericAclResourceFactory::class),

        // Basic factory
        AclResourceRulesCollectorFactoryInterface::class => DI\autowire(GenericAclResourceRulesCollectorFactory::class),

    ],

];
