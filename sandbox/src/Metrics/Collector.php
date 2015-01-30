<?php

namespace Metrics;

use Psr\Log\LoggerInterface;

class Collector
{
    protected $metricsProvider;
    protected $driver;
    protected $logger;

    public function __construct(MetricsProvider $metricsProvider, StatsdDriver $driver, LoggerInterface $logger = null)
    {
        $this->metricsProvider = $metricsProvider;
        $this->driver = $driver;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function send()
    {
        try {
            /** @var Metrics $metrics */
            $metrics = $this->metricsProvider->getMetrics();
            if (count($metrics)) {
                return $this->driver->sendMetrics($metrics);
            }
        } catch (\Exception $e) {
            if (!is_null($this->logger)) {
                $this->logger->critical('unable to send metrics');
            }
        }

        return false;
    }
}