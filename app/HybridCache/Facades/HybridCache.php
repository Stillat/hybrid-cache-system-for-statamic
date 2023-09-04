<?php

namespace App\HybridCache\Facades;

use App\HybridCache\Manager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool canHandle()
 * @method static void sendCachedResponse()
 * @method static string|null getCacheFileName()
 * @method static void registerViewPath(string $path)
 * @method static void registerEntryId(string $id)
 * @method static void registerAssetPath(string $path)
 * @method static void registerTermId(string $id)
 * @method static void registerGlobalPath(string $path)
 * @method static array getCacheData()
 * @method static bool canCache()
 * @method static void abandonCache()
 * @method static array getCacheableStatusCodes()
 * @method static bool getIgnoreQueryString()
 * @method static bool isCacheBypassed()
 * @method static void setExpiration(mixed $expiration)
 * @method static mixed getExpiration()
 * @method static void invalidateAll()
 * @method static void invalidateCacheLabel(string $namespace, ?string $label)
 * @method static void invalidateLabelNamespace(string $namespace)
 * @method static void invalidateLabel(string $namespace, string $label)
 * @method static void label(string $namespace, ?string $label = null)
 * @method static string labelName(string $namespace, string $label)
 * @method static string labelPath(string $namespace, string $label)
 * @method static array getLabels()
 * @method static array getLabelNamespaces()
 * @method
 *
 * @see \App\HybridCache\Facades\HybridCache
 */
class HybridCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Manager::class;
    }
}
