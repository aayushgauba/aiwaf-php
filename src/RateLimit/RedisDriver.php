<?php
namespace AIWAF\RateLimit;

class RedisDriver implements DriverInterface
{
    private \Redis $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function increment(string $ip, int $periodSeconds): int
    {
        $period = gmdate('YmdHi');            // e.g. 202504281405
        $key    = "rl:$ip:$period";
        $count  = $this->redis->incr($key);
        if ($count === 1) {
            $this->redis->expire($key, $periodSeconds);
        }
        return $count;
    }
}
