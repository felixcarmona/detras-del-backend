<?php

namespace MetricsBundle;

use MetricsBundle\DependencyInjection\Compiler\RegisterMetricsProviders;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MetricsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterMetricsProviders());
    }
}
