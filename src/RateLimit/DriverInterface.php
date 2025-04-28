<?php
namespace AIWAF\RateLimit;

interface DriverInterface
{
    /**
     * Increment this IP’s counter for the given period, return the new count.
     *
     * @param string $ip
     * @param int    $periodSeconds
     * @return int
     */
    public function increment(string $ip, int $periodSeconds): int;
}
