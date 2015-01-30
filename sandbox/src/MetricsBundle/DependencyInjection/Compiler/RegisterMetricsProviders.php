<?php

namespace MetricsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterMetricsProviders implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('metrics_provider');
        $taggedServices = $container->findTaggedServiceIds('metrics_provider');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'registerMetricsProvider',
                array($id)
            );
        }
    }
}