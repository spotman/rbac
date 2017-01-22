<?php
namespace Spotman\Acl\Initializer;

use Spotman\Acl\Acl;

interface InitializerInterface
{
    public function init(Acl $acl);
}
