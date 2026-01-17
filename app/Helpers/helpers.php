<?php

use SmartCache\Facades\SmartCache;

if (! function_exists('optimize')) {
    /**
     * Cache the result of the callback if optimization is enabled.
     *
     * @param  string  $key
     * @param  \Closure  $callback
     * @param  int  $ttl  Seconds to cache (default: 24 hours = 86400)
     * @param  array  $tags  Cache tags to apply (only works with Redis/Memcached)
     * @return mixed
     */
    function optimize($key, $callback, $ttl = 86400, array $tags = [])
    {
        if (config('app.optimize')) {
            // Use memo() for current request caching to speed up repeated accesses
            // This layers a static in-memory array cache on top of the backend cache
            $cache = SmartCache::memo();

            if (! empty($tags)) {
                return $cache->tags($tags)->remember($key, $ttl, $callback);
            }

            return $cache->remember($key, $ttl, $callback);
        }

        return $callback();
    }
}
