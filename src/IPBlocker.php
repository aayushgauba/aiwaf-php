<?php
class IPBlocker
{
    public static function blockIp(string $ip): void
    {
        $projectRoot = dirname(__DIR__);
        $path = $projectRoot . '/resources/blocked_ips.json';
        $blockedIps = Utils::loadJson($path);

        if (!in_array($ip, $blockedIps)) {
            $blockedIps[] = $ip;
            Utils::saveJson($path, $blockedIps);
            Utils::log("Blocking IP: $ip");
        }
    }

    public static function getBlockedIps(): array
    {
        $projectRoot = dirname(__DIR__);
        $path = $projectRoot . '/resources/blocked_ips.json';
        return Utils::loadJson($path);
    }
}
