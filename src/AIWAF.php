<?php
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/IPBlocker.php';
require_once __DIR__ . '/DynamicKeywordManager.php';
require_once __DIR__ . '/FeatureExtractor.php';
require_once __DIR__ . '/RateLimiter.php';
require_once __DIR__ . '/UUIDTamperProtector.php';
require_once __DIR__ . '/HoneypotChecker.php';
require_once __DIR__ . '/IsolationForest.php'; // ✅ add this line

class AIWAF
{
    public static function protect()
    {
        $ip = Utils::getClientIp();
        $path = Utils::getRequestPath();

        if (Utils::isExemptPath($path, Config::$exemptPaths)) {
            return;
        }

        if (in_array($ip, IPBlocker::getBlockedIps())) {
            Utils::log("Blocked IP tried to access: $ip");
            http_response_code(403);
            exit;
        }

        // ✅ Isolation Forest check starts here
        $featureVector = self::extractRequestFeatures();
        $iso = new IsolationForest();
        $modelPath = dirname(__DIR__) . '/resources/forest_model.json';

        if (file_exists($modelPath)) {
            $iso->loadModel($modelPath);
            $score = $iso->scoreSamples([$featureVector])[0];

            if ($score > 0.6) { // Threshold: adjust as needed
                IPBlocker::blockIp($ip);
                Utils::log("Anomaly detected and blocked: $ip with score $score");
                http_response_code(403);
                exit;
            }
        }

        // ✅ Continue normal heuristics (fallback if Isolation Forest not loaded)
        if (RateLimiter::check($ip)) {
            IPBlocker::blockIp($ip);
            http_response_code(429);
            exit;
        }

        if (DynamicKeywordManager::detect($path) >= Config::$keywordDetectionThreshold) {
            IPBlocker::blockIp($ip);
            http_response_code(403);
            exit;
        }

        if (UUIDTamperProtector::isSuspicious($path)) {
            IPBlocker::blockIp($ip);
            http_response_code(403);
            exit;
        }

        if (isset($_POST) && HoneypotChecker::hasTriggered($_POST)) {
            IPBlocker::blockIp($ip);
            http_response_code(403);
            exit;
        }
    }

    private static function extractRequestFeatures(): array
    {
        $path = Utils::getRequestPath();
        $features = [
            strlen($path), // path_len
            DynamicKeywordManager::detect($path), // kw_hits
            0.5, // Placeholder for response time (needs real timing logic)
            isset($_SERVER['REDIRECT_STATUS']) && in_array((int)$_SERVER['REDIRECT_STATUS'], [404, 500]) ? 1 : 0, // status_idx
            0, // burst_count (needs log analysis or session)
            0, // total_404 (same)
            isset($_POST['aiwaf_honeytrap']) ? 1 : 0, // honeypot triggered
            UUIDTamperProtector::isSuspicious($path) ? 1 : 0, // uuid tamper
        ];
        return $features;
    }
}
