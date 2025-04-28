<?php
namespace AIWAF\RateLimit;

class InMemoryDriver implements DriverInterface
{
    private array $cache = [];

    public function increment(string $ip, int $periodSeconds): int
    {
        $period = floor(time() / $periodSeconds);
        $key    = "$ip:$period";
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = 0;
        }
        return ++$this->cache[$key];
    }
}
