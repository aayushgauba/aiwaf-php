<?php
namespace AIWAF;

class Logger
{
    private static ?string $override = null;          // ← setter wins
    public  const ENV   = 'AIWAF_FEATURE_LOG';        // ← env var key
    public  const FALLBACK = __DIR__ . '/../resources/request_features.csv';

    /** Call once (e.g. in bootstrap) if you need a custom path */
    public static function setLogFile(string $path): void
    {
        self::$override = $path;
    }

    /** Where should we log right now? */
    private static function path(): string
    {
        if (self::$override) {
            return self::$override;
        }

        // env-var?  e.g. export AIWAF_FEATURE_LOG=/var/log/aiwaf.csv
        $env = getenv(self::ENV);
        if ($env && $env !== '') {
            return $env;
        }

        // project-wide default (can live in Config.php)
        return defined('\\AIWAF\\Config::FEATURE_LOG')
            ? Config::FEATURE_LOG
            : self::FALLBACK;
    }

    /** Append one feature vector */
    public static function log(array $vector): void
    {
        $fh = fopen(self::path(), 'a');
        fputcsv($fh, $vector);
        fclose($fh);
    }

    /** Load all rows */
    public static function readAll(): array
    {
        $file = self::path();
        if (!file_exists($file)) {
            return [];
        }
        return array_map(
            fn($r) => array_map('floatval', $r),
            array_map('str_getcsv', file($file, FILE_IGNORE_NEW_LINES))
        );
    }
}
