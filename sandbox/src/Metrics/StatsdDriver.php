<?php

namespace Metrics;

use Metrics\Exception\StatsdDriverException;

class StatsdDriver
{
    private $prefix;
    private $host;
    private $port;
    private $defaultSampling;

    /**
     * @param string|null $prefix
     * @param string|null $host
     * @param int|null $port
     * @param int|bool|null $defaultSampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78)
     */
    public function __construct($host = 'localhost', $port = 8125, $defaultSampling = 100, $prefix = '')
    {
        $this->prefix = $prefix;
        $this->host = $host;
        $this->port = $port;

        if (!is_null($defaultSampling)) {
            $defaultSampling = round($defaultSampling, 2);
        }

        $this->defaultSampling = $defaultSampling;
    }

    /**
     * @param  Metrics $metrics
     * @return bool
     */
    public function sendMetrics(Metrics $metrics)
    {
        if (count($metrics) == 0) {
            return false;
        }

        $sendableMetrics = $this->filterSendableMetricsBySampling($metrics);

        if (count($sendableMetrics) == 0) {
            return false;
        }

        if (!$filePointer = $this->openUdpSocket()) {
            return false;
        }

        foreach ($sendableMetrics as $metric) {
            $this->sendMetric($filePointer, $metric);
        }

        fclose($filePointer);

        return true;
    }

    /**
     * @param  Metrics $metrics
     * @return array
     */
    protected function filterSendableMetricsBySampling(Metrics $metrics)
    {
        $random = mt_rand(1, 10000);
        $sendableMetrics = array();
        foreach ($metrics as $metric) {
            if ($this->getMetricSampling($metric) * 100 > $random) {
                $sendableMetrics[] = $metric;
            }
        }
        return $sendableMetrics;
    }

    protected function getMetricSampling($metric)
    {
        if (isset($metric['sampling']) && !is_null($metric['sampling'])) {
            return $metric['sampling'];
        }
        return $this->defaultSampling;
    }

    protected function sendMetric($filePointer, $metric)
    {
        $sampling = $this->getMetricSampling($metric);
        $stat = $this->prefix . $metric['stat'];
        $value = $metric['value'] . '|' . $this->getStatsdTypeFromGenericType($metric['type']);
        if ($metric['type'] == 'counter') {
            $value .= '|@' . ($sampling / 100);
        }
        fwrite($filePointer, "$stat:$value");
    }

    protected function openUdpSocket()
    {
        return @fsockopen("udp://$this->host", $this->port);
    }

    protected function getStatsdTypeFromGenericType($type)
    {
        switch ($type) {
            case 'gauge':
                return 'g';
            case 'timing':
                return 'ms';
            case 'counter':
                return 'c';
            case 'set':
                return 's';
            default:
                throw new StatsdDriverException('Invalid generic type');
        }
    }
}