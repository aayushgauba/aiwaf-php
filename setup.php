<?php
$resourcesPath = __DIR__ . '/resources';
$blockedIpsFile = $resourcesPath . '/blocked_ips.json';

if (!file_exists($resourcesPath)) {
    mkdir($resourcesPath, 0777, true);
    echo "Created resources folder.\n";
}

if (!file_exists($blockedIpsFile)) {
    file_put_contents($blockedIpsFile, json_encode([], JSON_PRETTY_PRINT));
    echo "Created blocked_ips.json.\n";
} else {
    echo "blocked_ips.json already exists.\n";
}
