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

if (! function_exists('theme_view')) {
    /**
     * Get the evaluated view contents for the active storefront theme.
     *
     * @param  string|null  $view  Relative view path (e.g. 'pages.index')
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function theme_view($view = null, $data = [], $mergeData = [])
    {
        if (is_null($view)) {
            return view();
        }

        $theme = config('view.theme', 'araz');

        return view("{$theme}.{$view}", $data, $mergeData);
    }
}

