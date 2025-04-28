<?php
class RateLimiter
{
    private static $cache = [];

    public static function check(string $ip): bool
    {
        $minute = date('Y-m-d H:i');
        if (!isset(self::$cache[$ip][$minute])) {
            self::$cache[$ip][$minute] = 0;
        }
        self::$cache[$ip][$minute]++;
        return self::$cache[$ip][$minute] > Config::$rateLimitPerMinute;
    }
}
