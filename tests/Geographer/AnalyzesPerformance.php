<?php

declare(strict_types=1);

namespace Tests;

trait AnalyzesPerformance
{
    /**
     * @var float Maximum execution time in seconds
     */
    protected $performanceTimeGoal = 60;

    /**
     * @var int Maximum memory usage in bytes
     */
    protected $performanceMemoryGoal = 10000000;

    /**
     * @var int
     */
    protected $memoryUsage = 0;

    /**
     * @var int
     */
    protected $startTimestamp;

    public function performanceHook(): void
    {
        $this->memoryUsage = memory_get_usage();
        $this->startTimestamp = microtime(true);
    }

    public function performanceCheck(): void
    {
        $this->assertTrue((microtime(true) - $this->startTimestamp) < $this->performanceTimeGoal);
        $this->assertTrue(memory_get_usage() - $this->memoryUsage < $this->performanceMemoryGoal);
    }
}
