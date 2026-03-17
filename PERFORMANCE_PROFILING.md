# Performance Profiling Guide

## QueryProfiler Usage

The `QueryProfiler` class provides comprehensive database query analysis and performance monitoring.

### Location
`app/core/QueryProfiler.php`

### Features

- **Query Tracking**: Track all database queries with timing data
- **N+1 Detection**: Identify queries executed multiple times in a single request
- **Slow Query Analysis**: Find queries exceeding performance thresholds
- **Cache Hit Rate**: Monitor QueryCache effectiveness
- **Performance Reports**: Get comprehensive analysis with recommendations

### Basic Usage

```php
// Track a query execution
QueryProfiler::track(
    $query,              // SQL query string
    $params,             // Query parameters array
    $execution_time,     // Execution time in milliseconds
    $from_cache          // Boolean: whether result came from cache
);

// Get all tracked queries
$queries = QueryProfiler::getAllQueries();

// Get query count
$count = QueryProfiler::getQueryCount();

// Get performance report
$report = QueryProfiler::getReport();

// Display HTML report (development only)
echo QueryProfiler::displayReport();
```

### Performance Report Structure

```php
$report = [
    'total_queries'             => int,      // Total queries executed
    'total_execution_time_ms'   => float,    // Total time in milliseconds
    'average_query_time_ms'     => float,    // Average query duration
    'cache_hit_rate_percent'    => float,    // Cache effectiveness %
    'slow_queries_count'        => int,      // Number of slow queries
    'nplusone_queries_count'    => int,      // Number of repeated queries
    'nplusone_queries'          => array,    // List of problematic queries
    'slow_queries'              => array,    // List of slow queries
    'recommendations'           => array     // Optimization suggestions
];
```

### N+1 Query Detection

N+1 queries happen when a query is executed many times instead of using a batch operation.

```php
// Get queries executed 3+ times
$nplusone = QueryProfiler::getNPlusOneQueries(3);

// Output:
// [
//     'SELECT * FROM `users` WHERE `user_id` = ?' => 12,
//     'SELECT * FROM `messages` WHERE `message_ownerId` = ?' => 8
// ]
```

**Fix**: Use QueryCache to prevent duplicate queries:

```php
// Instead of looping and querying:
foreach ($users as $user) {
    $messages = NavbarLoader::getMessageType('messages', ['message_ownerId' => $user->user_id]);
}

// Use QueryCache:
$messages = QueryCache::remember(
    'messages_' . $user->user_id,
    fn() => NavbarLoader::getMessageType('messages', ['message_ownerId' => $user->user_id])
);
```

### Slow Query Detection

Queries exceeding 100ms threshold are flagged as slow.

```php
// Get slow queries
$slow = QueryProfiler::getSlowQueries(100); // 100ms threshold

foreach ($slow as $query_data) {
    echo "Query: " . $query_data['query'];
    echo "Time: " . $query_data['execution_time'] . "ms";
}
```

**Common causes:**
- Missing database indexes
- Inefficient WHERE clauses
- Large result sets
- Complex JOINs

**Fix**: Add appropriate database indexes (see `database/optimize_indexes.sql`)

### Cache Hit Rate Analysis

```php
$cache_stats = QueryProfiler::getCacheStats();

echo "Cache hits: " . $cache_stats['cache_hits'];
echo "Actual queries: " . $cache_stats['actual_queries'];
echo "Hit rate: " . $cache_stats['cache_hit_rate'] . "%";
```

**Optimal targets:**
- 40%+ cache hit rate for normal usage
- 60%+ for dashboard with heavy message/task queries

### Integration Example

Add profiling to your Database trait:

```php
// In Database.php query() method, after execution:

$start = microtime(true);
$check = $stmt->execute($data);
$exec_time = (microtime(true) - $start) * 1000; // Convert to ms

QueryProfiler::track($query, $data, $exec_time, false);
```

### Dashboard Integration

For development, add this to `template_footer.php`:

```php
<?php if (DEBUG): ?>
    <?= QueryProfiler::displayReport() ?>
<?php endif; ?>
```

This displays:
- Total queries executed
- Total execution time
- Average query performance
- Cache hit rate
- Identified N+1 patterns
- Performance recommendations

## Optimization Recommendations

### Current Implementation Status

✓ **Connection Pooling**: Singleton pattern eliminates per-query connection overhead
✓ **Query Caching**: QueryCache prevents duplicate queries within request
✓ **Prepared Statements**: All queries parameterized for database optimization
✓ **Session Security**: Minimizes session lookups

### Ready for Implementation

#### 1. Database Indexes (HIGH IMPACT)

File: `database/optimize_indexes.sql`

Add indexes to:
- users: email (UNIQUE), role
- messages: ownerId, authorId, createdAt
- tasks: ownerId
- notifications: ownerId

**Expected improvement**: 25-40% faster queries

#### 2. Query Profiling (MEDIUM EFFORT)

Integrate QueryProfiler::track() into Database trait methods

**Benefit**: Real-time performance monitoring

#### 3. Lazy Loading (MEDIUM EFFORT)

Load messages/tasks/notifications via AJAX on demand

**Benefit**: 15-25% faster initial page load

#### 4. Result Caching (MEDIUM EFFORT)

Consider Redis/Memcached for persistent cache across requests

**Benefit**: Reduced database load, faster repeated queries

## Monitoring Best Practices

1. **Development**: Enable QueryProfiler for all requests
   ```php
   QueryProfiler::setEnabled(DEBUG);
   ```

2. **Production**: Log profiling data periodically
   ```php
   if (rand(1, 100) === 1) { // Log 1% of requests
       logError("Production profiling", QueryProfiler::getReport(), 'info');
   }
   ```

3. **Alerts**: Set up alerts for:
   - Query count > 50 per request
   - Average query time > 50ms
   - Cache hit rate < 30%
   - Slow queries > 500ms

## Common Performance Issues & Solutions

### Issue: High Query Count (>50 queries/request)
**Cause**: N+1 queries or missing caching
**Solution**: Use QueryProfiler to find repeated queries, add QueryCache::remember()

### Issue: Slow Dashboard Load
**Cause**: Missing database indexes
**Solution**: Run `database/optimize_indexes.sql`

### Issue: Low Cache Hit Rate (<30%)
**Cause**: Insufficient caching strategy
**Solution**: Cache more frequently accessed data

### Issue: User Actions Taking >1 second
**Cause**: Database connection or slow query
**Solution**: Profile with QueryProfiler, add indexes if needed

## Tools & Resources

- **QueryProfiler**: Real-time query analysis
- **optimize_indexes.sql**: Database index recommendations
- **performance_analysis.php**: Architecture analysis and stats

## References

- PSR-12: PHP Naming Standards
- PSR-7: HTTP Message Interface (for future REST API)
- MySQL Query Optimization: https://dev.mysql.com/doc/refman/8.0/en/optimization.html
