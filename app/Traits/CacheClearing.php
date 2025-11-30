<?php

namespace App\Traits;

use SmartCache\Facades\SmartCache;
use Spatie\ResponseCache\Facades\ResponseCache;

trait CacheClearing
{
    public static function bootCacheClearing()
    {
        static::created(function ($model) {
            $model->clearCaches();
        });

        static::updated(function ($model) {
            $model->clearCaches();
        });

        static::deleted(function ($model) {
            $model->clearCaches();
        });
    }

    public function clearCaches()
    {
        // 1. Flush ResponseCache (Full Page Cache)
        if (class_exists(ResponseCache::class)) {
            ResponseCache::clear();
        }

        // 2. Flush Data Cache Tags
        // Default tag is the table name (e.g., 'products', 'settings')
        $tags = $this->getCacheTags();

        if (config('app.optimize') && !empty($tags)) {
             try {
                SmartCache::flushTags($tags);
            } catch (\Exception $e) {
                // In case driver doesn't support tags, strictly speaking we should only be here if optimized=true (redis)
            }
        }
    }

    protected function getCacheTags(): array
    {
        // Default to table name
        return [$this->getTable()];
    }
}

