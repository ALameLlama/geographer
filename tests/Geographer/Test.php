<?php

declare(strict_types=1);

namespace Tests;

abstract class Test extends \PHPUnit\Framework\TestCase
{
    use AnalyzesPerformance;

    protected function setUp(): void
    {
        $this->performanceHook();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->performanceCheck();
        parent::tearDown();
    }
}
