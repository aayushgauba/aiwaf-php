<?php
namespace AIWAF;

use AIWAF\RateLimit\DriverInterface;

class RateLimiter
{
    private static DriverInterface $driver;
    private const WINDOW = 60;

    public static function init(DriverInterface $driver): void
    {
        self::$driver = $driver;
    }

    public static function check(string $ip): bool
    {
        $count = self::$driver->increment($ip, self::WINDOW);
        return $count > Config::$rateLimitPerMinute;
    }
}
