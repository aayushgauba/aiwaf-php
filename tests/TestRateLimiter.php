<?php
declare(strict_types=1);

// 1) Interface must be defined before the driver
require_once __DIR__ . '/../src/RateLimit/DriverInterface.php';
// 2) Then load the driver
require_once __DIR__ . '/../src/RateLimit/InMemoryDriver.php';
// 3) Then load Config and RateLimiter
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/RateLimiter.php';

use PHPUnit\Framework\TestCase;
use AIWAF\Config;
use AIWAF\RateLimiter;
use AIWAF\RateLimit\InMemoryDriver;

class TestRateLimiter extends TestCase
{
    protected function setUp(): void
    {
        // Initialize with the in-memory driver
        RateLimiter::init(new InMemoryDriver());
    }

    public function testRateLimiting(): void
    {
        $ip = '127.0.0.1';

        // the first Config::$rateLimitPerMinute calls should pass
        for ($i = 1; $i <= Config::$rateLimitPerMinute; $i++) {
            $this->assertFalse(
                RateLimiter::check($ip),
                "Request #{$i} should NOT trigger rate limiting"
            );
        }

        // the very next request must be blocked
        $this->assertTrue(
            RateLimiter::check($ip),
            'Request exceeding the limit SHOULD trigger rate limiting'
        );
    }
}
