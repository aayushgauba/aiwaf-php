<?php
namespace AIWAF;

class Utils
{
    public static function loadJson(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    public static function saveJson(string $path, array $data): bool
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($path, $json) !== false;
    }

    public static function log(string $message): void
    {
        error_log('[AIWAF] ' . $message);
    }

    public static function isExemptPath(string $path, array $exemptPaths): bool
    {
        foreach ($exemptPaths as $pattern) {
            if (strpos($path, $pattern) === 0) {
                return true;
            }
        }
        return false;
    }

    public static function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function saveBlockedIps(array $blockedIps): void
    {
        $projectRoot = dirname(__DIR__);
        $resourcesPath = $projectRoot . '/resources';
        $blockedIpsFile = $resourcesPath . '/blocked_ips.json';

        if (!file_exists($resourcesPath)) {
            mkdir($resourcesPath, 0777, true);
        }
        if (!file_exists($blockedIpsFile)) {
            file_put_contents($blockedIpsFile, json_encode([], JSON_PRETTY_PRINT));
        }
        file_put_contents($blockedIpsFile, json_encode($blockedIps, JSON_PRETTY_PRINT));
    }

    public static function getRequestPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $queryPos = strpos($uri, '?');
        return $queryPos !== false ? substr($uri, 0, $queryPos) : $uri;
    }
}
