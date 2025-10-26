<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    /**
     * Cache course data
     */
    public static function cacheCourse($courseId, $data)
    {
        $key = "course:{$courseId}";
        $ttl = config('cache.ttl.courses', 3600);
        
        return Cache::tags(['courses'])->put($key, $data, $ttl);
    }

    /**
     * Get cached course data
     */
    public static function getCachedCourse($courseId)
    {
        $key = "course:{$courseId}";
        return Cache::tags(['courses'])->get($key);
    }

    /**
     * Cache course list with filters
     */
    public static function cacheCourseList($filters, $data)
    {
        $key = "courses:list:" . md5(serialize($filters));
        $ttl = config('cache.ttl.courses', 3600);
        
        return Cache::tags(['courses', 'search'])->put($key, $data, $ttl);
    }

    /**
     * Get cached course list
     */
    public static function getCachedCourseList($filters)
    {
        $key = "courses:list:" . md5(serialize($filters));
        return Cache::tags(['courses', 'search'])->get($key);
    }

    /**
     * Cache user data
     */
    public static function cacheUser($userId, $data)
    {
        $key = "user:{$userId}";
        $ttl = config('cache.ttl.users', 1800);
        
        return Cache::tags(['users'])->put($key, $data, $ttl);
    }

    /**
     * Get cached user data
     */
    public static function getCachedUser($userId)
    {
        $key = "user:{$userId}";
        return Cache::tags(['users'])->get($key);
    }

    /**
     * Cache categories
     */
    public static function cacheCategories($data)
    {
        $key = "categories:all";
        $ttl = config('cache.ttl.categories', 7200);
        
        return Cache::tags(['categories'])->put($key, $data, $ttl);
    }

    /**
     * Get cached categories
     */
    public static function getCachedCategories()
    {
        $key = "categories:all";
        return Cache::tags(['categories'])->get($key);
    }

    /**
     * Cache analytics data
     */
    public static function cacheAnalytics($key, $data)
    {
        $cacheKey = "analytics:{$key}";
        $ttl = config('cache.ttl.analytics', 900);
        
        return Cache::tags(['analytics'])->put($cacheKey, $data, $ttl);
    }

    /**
     * Get cached analytics data
     */
    public static function getCachedAnalytics($key)
    {
        $cacheKey = "analytics:{$key}";
        return Cache::tags(['analytics'])->get($cacheKey);
    }

    /**
     * Cache API response
     */
    public static function cacheApiResponse($endpoint, $params, $data)
    {
        $key = "api:" . md5($endpoint . serialize($params));
        $ttl = config('cache.ttl.api_responses', 300);
        
        return Cache::tags(['api'])->put($key, $data, $ttl);
    }

    /**
     * Get cached API response
     */
    public static function getCachedApiResponse($endpoint, $params)
    {
        $key = "api:" . md5($endpoint . serialize($params));
        return Cache::tags(['api'])->get($key);
    }

    /**
     * Clear cache by tags
     */
    public static function clearByTags($tags)
    {
        if (is_string($tags)) {
            $tags = [$tags];
        }
        
        return Cache::tags($tags)->flush();
    }

    /**
     * Clear all course-related cache
     */
    public static function clearCourseCache()
    {
        return self::clearByTags(['courses', 'search']);
    }

    /**
     * Clear user cache
     */
    public static function clearUserCache($userId = null)
    {
        if ($userId) {
            $key = "user:{$userId}";
            return Cache::tags(['users'])->forget($key);
        }
        
        return self::clearByTags(['users']);
    }

    /**
     * Clear category cache
     */
    public static function clearCategoryCache()
    {
        return self::clearByTags(['categories']);
    }

    /**
     * Clear analytics cache
     */
    public static function clearAnalyticsCache()
    {
        return self::clearByTags(['analytics']);
    }

    /**
     * Clear all cache
     */
    public static function clearAll()
    {
        return Cache::flush();
    }

    /**
     * Get cache statistics
     */
    public static function getStats()
    {
        try {
            $redis = Redis::connection('cache');
            $info = $redis->info('memory');
            
            return [
                'memory_used' => $info['used_memory_human'] ?? 'N/A',
                'memory_peak' => $info['used_memory_peak_human'] ?? 'N/A',
                'keys_count' => $redis->dbsize(),
                'hit_rate' => self::calculateHitRate(),
            ];
        } catch (\Exception $e) {
            return [
                'memory_used' => 'N/A',
                'memory_peak' => 'N/A',
                'keys_count' => 'N/A',
                'hit_rate' => 'N/A',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate cache hit rate (simplified)
     */
    private static function calculateHitRate()
    {
        try {
            $redis = Redis::connection('cache');
            $info = $redis->info('stats');
            
            $hits = $info['keyspace_hits'] ?? 0;
            $misses = $info['keyspace_misses'] ?? 0;
            $total = $hits + $misses;
            
            if ($total === 0) {
                return '0%';
            }
            
            $rate = ($hits / $total) * 100;
            return round($rate, 2) . '%';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Warm up cache with essential data
     */
    public static function warmUp()
    {
        // Cache categories
        $categories = \App\Models\Category::all();
        self::cacheCategories($categories);

        // Cache levels
        $levels = \App\Models\Level::all();
        Cache::tags(['settings'])->put('levels:all', $levels, config('cache.ttl.settings'));

        // Cache terms
        $terms = \App\Models\Term::all();
        Cache::tags(['settings'])->put('terms:all', $terms, config('cache.ttl.settings'));

        return true;
    }
}
