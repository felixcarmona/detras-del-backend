<?php

namespace Metrics;

use Metrics\Exception\MetricsException;

class Metrics implements \Iterator, \Countable
{
    public $data = array();

    protected $startedTimers = array();

    public function startTimer($stat)
    {
        $this->startedTimers[$stat] = microtime(true);
    }

    /**
     * @param string $stat The metric to set
     * @param int $ms The time value
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     */
    public function timing($stat, $ms, $sampling = null)
    {
        $this->data[] = array('type' => 'timing', 'stat' => $stat, 'value' => $ms, 'sampling' => $sampling);
    }

    /**
     * @param $stat
     * @param  bool $setStat
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     * @return int
     * @throws MetricsException
     */
    public function stopTimer($stat, $setStat = true, $sampling = null)
    {
        if (!array_key_exists($stat, $this->startedTimers)) {
            throw new MetricsException('start timing first');
        }
        $ms = (microtime(true) - $this->startedTimers[$stat]) * 1000;
        unset($this->startedTimers[$stat]);
        if ($setStat) {
            $this->timing($stat, $ms, $sampling);
        }
        return $ms;
    }

    /**
     * @param string $stat The metric to set.
     * @param float $value The value for the stat.
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     **/
    public function gauge($stat, $value, $sampling = null)
    {
        $this->data[] = array('type' => 'gauge', 'stat' => $stat, 'value' => $value, 'sampling' => $sampling);
    }

    /**
     * Increments a stat counter.
     *
     * @param string $stat The metric to increment.
     * @param int|float $amount the amount to increment.
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     **/
    public function increment($stat, $amount = 1, $sampling = null)
    {
        $this->data[] = array('type' => 'counter', 'stat' => $stat, 'value' => $amount, 'sampling' => $sampling);
    }

    /**
     * Decrements a stat counter.
     *
     * @param string $stat The metric to decrement.
     * @param int|float $amount the amount to decrement.
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     **/
    public function decrement($stat, $amount = 1, $sampling = null)
    {
        $this->increment($stat, -$amount, $sampling);
    }

    /**
     * Sets are acting like simple counters (increment/decrement), with the additional specificity that it ignores duplicate values.
     *
     * @param $stat
     * @param $value
     * @param int|bool|null $sampling 0 - 100, equals to sample ratio percentage, two decimals precision allowed (i.e 50.78) (null = default)
     */
    public function set($stat, $value, $sampling = null)
    {
        $this->data[] = array('type' => 'set', 'stat' => $stat, 'value' => $value, 'sampling' => $sampling);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function valid()
    {
        $key = key($this->data);
        return !is_null($key) && $key !== false;
    }

    public function count()
    {
        return count($this->data);
    }
}