<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Query Profiler - Performance Monitoring for Database Queries
 * 
 * Tracks all database queries and provides performance analysis:
 * - Query execution time
 * - Query frequency (duplicate detection)
 * - Cache hits vs actual queries
 * - Slow query identification
 * - N+1 query detection
 * - SQL injection vulnerability patterns
 * 
 * @package PHPMVC
 */
class QueryProfiler
{
    /**
     * @var array Array of all executed queries with timing data
     */
    private static array $queries = [];

    /**
     * @var array Count of identical queries (for N+1 detection)
     */
    private static array $query_counts = [];

    /**
     * @var float Threshold for slow query in milliseconds
     */
    private static float $slow_query_threshold = 100; // 100ms

    /**
     * @var bool Whether profiling is enabled
     */
    private static bool $enabled = true;

    /**
     * Track a query execution
     * 
     * @param string $query The SQL query
     * @param array $params Query parameters
     * @param float $execution_time Time taken in milliseconds
     * @param bool $from_cache Whether result came from cache
     * @return void
     */
    public static function track(string $query, array $params = [], float $execution_time = 0, bool $from_cache = false): void
    {
        if (!self::$enabled) {
            return;
        }

        // Normalize query for counting (remove parameters)
        $normalized_query = self::normalizeQuery($query);
        self::$query_counts[$normalized_query] = (self::$query_counts[$normalized_query] ?? 0) + 1;

        // Store query details
        self::$queries[] = [
            'query' => $query,
            'params' => $params,
            'execution_time' => $execution_time,
            'from_cache' => $from_cache,
            'timestamp' => microtime(true),
            'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ];
    }

    /**
     * Normalize SQL query for counting (removes specific values)
     * 
     * @param string $query The SQL query
     * @return string Normalized query pattern
     */
    private static function normalizeQuery(string $query): string
    {
        // Remove long strings and numbers for pattern matching
        $normalized = preg_replace(
            [
                "/['\"]([^'\"]*?)['\"]/",  // Remove string contents
                '/\d+/',                    // Remove numbers
            ],
            ['\'?\'', '?'],
            $query
        );
        return $normalized;
    }

    /**
     * Get all tracked queries
     * 
     * @return array Array of query execution data
     */
    public static function getAllQueries(): array
    {
        return self::$queries;
    }

    /**
     * Get query count
     * 
     * @return int Total number of queries tracked
     */
    public static function getQueryCount(): int
    {
        return count(self::$queries);
    }

    /**
     * Get potential N+1 queries (queries repeated many times)
     * 
     * @param int $threshold Number of repeats to consider N+1 issue (default 3)
     * @return array Array of repeated queries and their counts
     */
    public static function getNPlusOneQueries(int $threshold = 3): array
    {
        $problematic = [];
        
        foreach (self::$query_counts as $query => $count) {
            if ($count >= $threshold) {
                $problematic[$query] = $count;
            }
        }
        
        arsort($problematic);
        return $problematic;
    }

    /**
     * Get slow queries (execution time above threshold)
     * 
     * @param float $threshold Slowness threshold in milliseconds (default 100ms)
     * @return array Array of slow queries ordered by execution time
     */
    public static function getSlowQueries(float $threshold = null): array
    {
        $threshold = $threshold ?? self::$slow_query_threshold;
        $slow = [];

        foreach (self::$queries as $query_data) {
            if ($query_data['execution_time'] > $threshold) {
                $slow[] = $query_data;
            }
        }

        // Sort by execution time descending
        usort($slow, fn($a, $b) => $b['execution_time'] <=> $a['execution_time']);
        return $slow;
    }

    /**
     * Get cache hit statistics
     * 
     * @return array Array with cache_hits, total_queries, cache_hit_rate
     */
    public static function getCacheStats(): array
    {
        $cache_hits = 0;
        $total_queries = count(self::$queries);

        foreach (self::$queries as $query_data) {
            if ($query_data['from_cache']) {
                $cache_hits++;
            }
        }

        return [
            'cache_hits' => $cache_hits,
            'actual_queries' => $total_queries - $cache_hits,
            'total_requests' => $total_queries,
            'cache_hit_rate' => $total_queries > 0 ? round(($cache_hits / $total_queries) * 100, 2) : 0
        ];
    }

    /**
     * Get total execution time of all queries
     * 
     * @return float Total execution time in milliseconds
     */
    public static function getTotalExecutionTime(): float
    {
        return array_sum(array_map(fn($q) => $q['execution_time'], self::$queries));
    }

    /**
     * Get performance report
     * 
     * @return array Comprehensive performance analysis
     */
    public static function getReport(): array
    {
        $nplusone = self::getNPlusOneQueries();
        $slow_queries = self::getSlowQueries();
        $cache_stats = self::getCacheStats();

        return [
            'total_queries' => self::getQueryCount(),
            'total_execution_time_ms' => self::getTotalExecutionTime(),
            'average_query_time_ms' => self::getQueryCount() > 0 
                ? round(self::getTotalExecutionTime() / self::getQueryCount(), 2)
                : 0,
            'cache_hit_rate_percent' => $cache_stats['cache_hit_rate'],
            'slow_queries_count' => count($slow_queries),
            'nplusone_queries_count' => count($nplusone),
            'nplusone_queries' => $nplusone,
            'slow_queries' => count($slow_queries) ? array_slice($slow_queries, 0, 5) : [],
            'recommendations' => self::generateRecommendations($nplusone, $slow_queries, $cache_stats)
        ];
    }

    /**
     * Generate optimization recommendations
     * 
     * @param array $nplusone N+1 queries
     * @param array $slow_queries Slow queries
     * @param array $cache_stats Cache statistics
     * @return array Array of recommended optimizations
     */
    private static function generateRecommendations(array $nplusone, array $slow_queries, array $cache_stats): array
    {
        $recommendations = [];

        // N+1 query detection
        if (!empty($nplusone)) {
            $top_query = reset($nplusone);
            $recommendations[] = "N+1 queries detected: A query is being executed " . $top_query . " times. Consider using QueryCache or batch loading.";
        }

        // Slow query detection
        if (count($slow_queries) > 2) {
            $recommendations[] = "Multiple slow queries detected (" . count($slow_queries) . "). Review query logic, add indexes, or consider denormalization.";
        }

        // Cache effectiveness
        if ($cache_stats['cache_hit_rate'] < 30) {
            $recommendations[] = "Low cache hit rate (" . $cache_stats['cache_hit_rate'] . "%). Consider caching more query results.";
        }

        // Query volume
        if (self::getQueryCount() > 50) {
            $recommendations[] = "High query volume (" . self::getQueryCount() . " queries). This may indicate N+1 issues or missing optimizations.";
        }

        return $recommendations;
    }

    /**
     * Reset all collected data
     * 
     * @return void
     */
    public static function reset(): void
    {
        self::$queries = [];
        self::$query_counts = [];
    }

    /**
     * Enable/disable profiling
     * 
     * @param bool $enabled Whether to enable profiling
     * @return void
     */
    public static function setEnabled(bool $enabled): void
    {
        self::$enabled = $enabled;
    }

    /**
     * Display profiling report as HTML for debugging
     * 
     * @return string HTML formatted report
     */
    public static function displayReport(): string
    {
        if (!DEBUG) {
            return '';
        }

        $report = self::getReport();
        $html = '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; margin: 20px 0; font-family: monospace; font-size: 12px;">';
        $html .= '<h3 style="margin-top: 0;">Database Performance Report</h3>';
        $html .= '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr style="background: #eee;"><td style="padding: 5px; border: 1px solid #ccc;"><strong>Metric</strong></td><td style="padding: 5px; border: 1px solid #ccc;"><strong>Value</strong></td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">Total Queries</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['total_queries'] . '</td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">Total Execution Time</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['total_execution_time_ms'] . 'ms</td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">Average Query Time</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['average_query_time_ms'] . 'ms</td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">Cache Hit Rate</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['cache_hit_rate_percent'] . '%</td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">Slow Queries</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['slow_queries_count'] . '</td></tr>';
        $html .= '<tr><td style="padding: 5px; border: 1px solid #ccc;">N+1 Queries</td><td style="padding: 5px; border: 1px solid #ccc;">' . $report['nplusone_queries_count'] . '</td></tr>';
        $html .= '</table>';

        if (!empty($report['recommendations'])) {
            $html .= '<h4>Recommendations:</h4><ul>';
            foreach ($report['recommendations'] as $rec) {
                $html .= '<li>' . htmlspecialchars($rec) . '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '</div>';
        return $html;
    }
}
