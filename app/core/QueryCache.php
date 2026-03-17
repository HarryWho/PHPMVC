<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Simple request-level query result cache
 * Caches results within a single request to avoid duplicate queries
 * Data is cleared at the end of the request
 */
class QueryCache
{
    /**
     * In-memory cache for this request
     */
    private static array $cache = [];

    /**
     * Get a cached value or execute the callback and cache result
     *
     * @param string $key Unique cache key
     * @param callable $callback Function that returns the value to cache
     * @return mixed The cached or freshly computed value
     */
    public static function remember(string $key, callable $callback): mixed
    {
        // Return cached value if exists
        if (isset(self::$cache[$key])) {
            if (DEBUG) {
                logError("Cache hit: $key", [], 'debug');
            }
            return self::$cache[$key];
        }

        // Execute callback and cache result
        $result = $callback();
        self::$cache[$key] = $result;

        if (DEBUG) {
            logError("Cache miss (computed): $key", [], 'debug');
        }

        return $result;
    }

    /**
     * Manually set a cache value
     *
     * @param string $key Unique cache key
     * @param mixed $value The value to cache
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        self::$cache[$key] = $value;
    }

    /**
     * Get a cache value without executing callback
     *
     * @param string $key The cache key
     * @return mixed|null The cached value or null if not found
     */
    public static function get(string $key): mixed
    {
        return self::$cache[$key] ?? null;
    }

    /**
     * Check if a key exists in cache
     *
     * @param string $key The cache key
     * @return bool True if cached, false otherwise
     */
    public static function has(string $key): bool
    {
        return isset(self::$cache[$key]);
    }

    /**
     * Delete a cache entry
     *
     * @param string $key The cache key
     * @return void
     */
    public static function forget(string $key): void
    {
        unset(self::$cache[$key]);
    }

    /**
     * Clear all cache for this request
     *
     * @return void
     */
    public static function flush(): void
    {
        self::$cache = [];
    }

    /**
     * Get all cached keys (for debugging)
     *
     * @return array List of cache keys
     */
    public static function getCacheKeys(): array
    {
        return array_keys(self::$cache);
    }

    /**
     * Get cache size (for debugging)
     *
     * @return int Number of cached items
     */
    public static function getCacheSize(): int
    {
        return count(self::$cache);
    }
}
