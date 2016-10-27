<?php

namespace App\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\FrontBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class AppFrontBundle extends Bundle
{
    public function boot()
    {
        
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
