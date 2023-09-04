<?php

namespace App\HybridCache\Providers;

use App\HybridCache\Commands\CacheLabels;
use App\HybridCache\Commands\CacheReport;
use App\HybridCache\Commands\InvalidateAll;
use App\HybridCache\Commands\InvalidateLabel;
use App\HybridCache\Facades\HybridCache;
use App\HybridCache\Manager;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class HybridCacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Manager::class, function () {
            if (Manager::$instance != null) {
                return Manager::$instance;
            }

            return new Manager();
        });

        $this->app->afterResolving(EncryptCookies::class, function (EncryptCookies $encryptCookies) {
            $encryptCookies->disableFor('X-Hybrid-Cache');
        });
    }

    public function boot()
    {
        $cacheStoragePath = storage_path('hybrid-cache');
        $labelsPath = $cacheStoragePath.'/labels';
        $globalInvalidationPath = $cacheStoragePath.'/global-invalidation';

        if (! file_exists($cacheStoragePath)) {
            mkdir(storage_path('hybrid-cache'), 0755, true);
        }

        if (! file_exists($labelsPath)) {
            mkdir($labelsPath, 0755, true);
        }

        if (! file_exists($globalInvalidationPath)) {
            touch($globalInvalidationPath);
        }

        view()->composer('*', function (View $view) {
            HybridCache::registerViewPath($view->getPath());
        });

        if ($this->app->runningInConsole()) {
            $this->commands(
                InvalidateAll::class,
                InvalidateLabel::class,
                CacheLabels::class,
                CacheReport::class,
            );
        }
    }
}
