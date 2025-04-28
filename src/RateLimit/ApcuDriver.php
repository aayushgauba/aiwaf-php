<?php
namespace AIWAF\RateLimit;

class ApcuDriver implements DriverInterface
{
    public function increment(string $ip, int $periodSeconds): int
    {
        $period = floor(time() / $periodSeconds);
        $key = "rl:$ip:$period";
        // apcu_add only succeeds if key did not exist
        if (apcu_add($key, 1, $periodSeconds)) {
            return 1;
        }
        return apcu_inc($key);
    }
}
