<?php
// Required classes
require_once __DIR__ . '/../src/RateLimiter.php';
require_once __DIR__ . '/../src/Config.php';

use PHPUnit\Framework\TestCase;

class TestRateLimiter extends TestCase
{
    public function testRateLimiting()
    {
        $ip = '127.0.0.1';

        // Simulate sending requests up to the allowed limit
        for ($i = 0; $i < Config::$rateLimitPerMinute; $i++) {
            $this->assertFalse(RateLimiter::check($ip), "Request $i should not trigger rate limiting yet");
        }

        // This request should now trigger the rate limiter
        $this->assertTrue(RateLimiter::check($ip), "Request exceeding limit should trigger rate limiting");
    }
}
