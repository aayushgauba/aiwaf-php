<?php
class Config
{
    public static $exemptPaths = [
        '/health',
        '/status',
    ];

    public static $rateLimitPerMinute = 60;
    public static $keywordDetectionThreshold = 5;
    public static $uuidTamperThreshold = 3;
}
