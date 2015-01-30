<?php

namespace MetricsBundle\Service;

use Metrics\Metrics;
use Metrics\MetricsProvider;
use Symfony\Component\DependencyInjection\Container;

class TaggedMetricsProvider implements MetricsProvider
{
    protected $contractedServices = array();
    protected $metrics;

    public function registerMetricsProvider($serviceName)
    {
        $this->contractedServices[] = $serviceName;
    }

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->metrics = new Metrics();
    }

    /**
     * @return Metrics
     */
    public function getMetrics()
    {
        foreach ($this->contractedServices as $service) {
            $serviceMetrics = $this->container->get($service)->getMetrics();
            foreach ($serviceMetrics as $data) {
                $this->metrics->data[] = $data;
            }
        }
        return $this->metrics;
    }
}