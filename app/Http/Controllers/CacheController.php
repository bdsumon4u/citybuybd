<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SmartCache\Facades\SmartCache;
use Spatie\ResponseCache\Facades\ResponseCache;

class CacheController extends Controller
{
    /**
     * Clear all application caches (ResponseCache, SmartCache, Artisan Cache)
     */
    public function clear()
    {
        try {
            // 1. Clear ResponseCache (Full Page Cache)
            if (class_exists(ResponseCache::class)) {
                ResponseCache::clear();
            }

            // 2. Clear SmartCache
            // This might throw exception if driver doesn't support flushing, so try-catch
            try {
                if (method_exists(SmartCache::class, 'flush')) {
                    SmartCache::flush();
                } else {
                     // Fallback to standard Cache flush if SmartCache facade just proxies to Cache
                     \Illuminate\Support\Facades\Cache::flush();
                }
            } catch (\Exception $e) {
                // Log or ignore if specific driver issue
            }

            // 3. Run artisan commands for view, config, route, etc.
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            
            // 4. Run optimize if APP_OPTIMIZE is true
            if (config('app.optimize')) {
                \Illuminate\Support\Facades\Artisan::call('optimize');
            }

            $notification = array(
                'message'    => 'System cache cleared successfully!',
                'alert-type' => 'success'
            );

        } catch (\Exception $e) {
            $notification = array(
                'message'    => 'Failed to clear cache: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->back()->with($notification);
    }
}
