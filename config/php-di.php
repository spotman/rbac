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
        AclInterface::class                              => DI\object(Acl::class)->lazy(),

        // Current user
        AclUserInterface::class                          => DI\get('User'),

        // Basic initializer for DI containers with autowiring ("Lazy initialization" pattern)
        AclInitializerInterface::class                   => DI\object(GenericAclInitializer::class)->lazy(),

        // Resolving resources` access relatively to current user
        AclAccessResolverInterface::class                => DI\object(UserAccessResolver::class)->lazy(),

        // No roles by default
        AclRolesCollectorInterface::class                => DI\object(EmptyAclRolesCollector::class),

        // No resources by default
        AclResourcesCollectorInterface::class            => DI\object(EmptyAclResourcesCollector::class),

        // No permissions by default
        AclRulesCollectorInterface::class                => DI\object(EmptyAclRulesCollector::class),

        // Simple factory without DI
        AclResourceFactoryInterface::class               => DI\object(GenericAclResourceFactory::class),

        // Basic factory
        AclResourceRulesCollectorFactoryInterface::class => DI\object(GenericAclResourceRulesCollectorFactory::class),

    ],

];
