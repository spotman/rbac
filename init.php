<?php

use \Spotman\Acl\Acl;
use \Spotman\Acl\Initializer\InitializerInterface;

$di = \BetaKiller\DI\Container::instance();

$di->call(function(InitializerInterface $initializer) {
    $acl = Acl::instance();
    $initializer->init($acl);
});
