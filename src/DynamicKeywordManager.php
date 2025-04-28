<?php
class DynamicKeywordManager
{
    private static $keywords = ['admin', '.env', '.git', 'phpmyadmin'];

    public static function detect(string $path): int
    {
        $count = 0;
        foreach (self::$keywords as $keyword) {
            if (strpos($path, $keyword) !== false) {
                $count++;
            }
        }
        return $count;
    }

    public static function learn(string $path): void
    {
        if (!in_array($path, self::$keywords)) {
            self::$keywords[] = $path;
        }
    }

    public static function addKeywords(array $newKeywords): bool
    {
        foreach ($newKeywords as $keyword) {
            if (!in_array($keyword, self::$keywords)) {
                self::$keywords[] = $keyword;
            }
        }
        return true;
    }

    public static function removeKeywords(array $keywordsToRemove): bool
    {
        self::$keywords = array_values(array_filter(self::$keywords, function ($keyword) use ($keywordsToRemove) {
            return !in_array($keyword, $keywordsToRemove);
        }));
        return true;
    }

    public static function resetKeywords(): void
    {
        self::$keywords = ['admin', '.env', '.git', 'phpmyadmin'];
    }

    public static function getKeywords(): array
    {
        return self::$keywords;
    }
}
