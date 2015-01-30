<?php

namespace Metrics;

interface MetricsProvider
{
    /** @return Metrics */
    public function getMetrics();
}