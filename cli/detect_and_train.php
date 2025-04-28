<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../src/AIWAF.php';

$waf = new AIWAF();
$waf->detectAndTrain();

echo "[AIWAF] detectAndTrain completed successfully.\n";
