<?php
class FeatureExtractor
{
    public static function extractFeatures(array $request): array
    {
        $features = [
            'path_len' => strlen($request['path'] ?? ''),
            'kw_hits' => count(array_intersect(['admin', 'config', 'wp-', '.env'], explode('/', $request['path'] ?? ''))),
            'resp_time' => $request['resp_time'] ?? 0,
            'status_idx' => in_array($request['status'], [404, 403, 500]) ? 1 : 0,
            'burst_count' => $request['burst_count'] ?? 0,
            'total_404' => $request['total_404'] ?? 0,
        ];
        return $features;
    }
}
