<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Closure;
use Illuminate\Routing\Events\ResponsePrepared;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;

class ResponsePreparedListener
{
    public function handle(ResponsePrepared $event)
    {
        if (request()->ajax()) {
            return;
        }

        if (! HybridCache::canCache()) {
            return;
        }

        // Don't cache responses for authenticated users.
        if (Auth::check()) {
            return;
        }

        cookie()->queue(cookie()->forever('X-Hybrid-Cache', 'false'));

        if (! in_array($event->response->getStatusCode(), HybridCache::getCacheableStatusCodes())) {
            return;
        }

        $cacheFileName = HybridCache::getCacheFileName();

        if (! $cacheFileName) {
            return;
        }

        $content = $event->response->getContent();

        if (mb_strlen($content) == 0) {
            return;
        }

        if (str_contains($content, csrf_token())) {
            return;
        }

        $responseDependencies = HybridCache::getCacheData();

        $paths = [
            storage_path('hybrid-cache/global-invalidation'),
        ];

        $cacheLabels = array_merge(HybridCache::getLabels(), HybridCache::getLabelNamespaces());

        foreach ($cacheLabels as $label) {
            $labelPath = storage_path('hybrid-cache/labels/'.$label);

            if (! file_exists($labelPath)) {
                touch($labelPath);
            }

            $paths[] = $labelPath;
        }

        // Handle routes.
        $currentRoute = Route::current();

        if ($currentRoute) {
            $uses = $currentRoute->getAction('uses');

            try {
                if ($uses instanceof Closure) {
                    $reflection = new ReflectionFunction($uses);

                    $paths[] = $reflection->getFileName();

                } elseif (is_string($uses) && $currentRoute->getControllerClass()) {
                    $reflection = new ReflectionClass($currentRoute->getControllerClass());

                    $paths[] = $reflection->getFileName();
                }
            } catch (ReflectionException $e) {
                return;
            }
        }

        $paths = array_merge($paths, $responseDependencies['viewPaths']);
        $paths = array_merge($paths, $responseDependencies['globalPaths']);

        $paths = array_merge($paths, Entry::query()
            ->whereIn('id', $responseDependencies['entryIds'])
            ->get()
            ->map(fn ($entry) => $entry->path())
            ->all());

        $paths = array_merge($paths, Term::query()
            ->whereIn('id', $responseDependencies['termIds'])
            ->get()
            ->map(fn ($term) => $term->path())
            ->all());

        $paths = array_merge($paths, $responseDependencies['assetPaths']);

        $timestamps = [];

        foreach ($paths as $path) {
            $timestamps[$path] = filemtime($path);
        }

        $headers = $event->response->headers->all();

        unset($headers['set-cookie']);
        unset($headers['date']);

        $cacheData = [
            'expires' => HybridCache::getExpiration(),
            'content' => $content,
            'paths' => $timestamps,
            'headers' => $headers,
        ];

        file_put_contents($cacheFileName, json_encode($cacheData));
    }
}
