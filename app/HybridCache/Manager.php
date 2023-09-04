<?php

namespace App\HybridCache;

class Manager
{
    protected bool $canCache = true;

    protected ?string $cacheFileName = null;

    protected array $entryIds = [];

    protected array $termIds = [];

    protected array $globalPaths = [];

    protected array $viewPaths = [];

    protected array $assetPaths = [];

    public static ?Manager $instance = null;

    protected array $configuration = [];

    protected mixed $expiration = null;

    protected array $labelNamespaces = [];

    protected array $labels = [];

    public function __construct()
    {
        self::$instance = $this;

        $configurationPath = realpath(__DIR__.'/../../config/hybrid-cache.php');

        if ($configurationPath) {
            $this->configuration = require $configurationPath;
        }
    }

    public function invalidateCacheLabel(string $namespace, ?string $label): void
    {
        if ($label == null) {
            $this->invalidateLabelNamespace($namespace);

            return;
        }

        $this->invalidateLabel($namespace, $label);
    }

    public function invalidateLabelNamespace(string $namespace): void
    {
        touch(storage_path('hybrid-cache/labels/'.$namespace));
    }

    public function invalidateLabel(string $namespace, string $label): void
    {
        touch($this->labelPath($namespace, $label));
    }

    public function label(string $namespace, string $label = null): void
    {
        if (! in_array($namespace, $this->labelNamespaces)) {
            $this->labelNamespaces[] = $namespace;
        }

        if ($label) {
            $newLabel = $this->labelName($namespace, $label);

            if (! in_array($newLabel, $this->labels)) {
                $this->labels[] = $newLabel;
            }
        }
    }

    public function labelName(string $namespace, string $label): string
    {
        return $namespace.'__'.$label;
    }

    public function labelPath(string $namespace, string $label): string
    {
        return storage_path('hybrid-cache/labels/'.$this->labelName($namespace, $label));
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getLabelNamespaces(): array
    {
        return $this->labelNamespaces;
    }

    public function invalidateAll(): void
    {
        touch(storage_path('hybrid-cache/global-invalidation'));
    }

    public function setExpiration(mixed $expiration): void
    {
        if ($expiration < time()) {
            $this->canCache = false;

            return;
        }

        if ($this->expiration != null) {
            if ($expiration < $this->expiration) {
                $this->expiration = $expiration;
            }

            return;
        }

        $this->expiration = $expiration;
    }

    public function getExpiration(): mixed
    {
        return $this->expiration;
    }

    public function canCache(): bool
    {
        return $this->canCache;
    }

    public function abandonCache(): void
    {
        $this->canCache = false;
    }

    public function getCacheFileName(): ?string
    {
        return $this->cacheFileName;
    }

    public function registerEntryId(string $id): void
    {
        if (! in_array($id, $this->entryIds)) {
            $this->entryIds[] = $id;
        }
    }

    public function registerTermId(string $id): void
    {
        if (! in_array($id, $this->termIds)) {
            $this->termIds[] = $id;
        }
    }

    public function registerGlobalPath(string $path): void
    {
        if (! in_array($path, $this->globalPaths)) {
            $this->globalPaths[] = $path;
        }
    }

    public function registerViewPath(string $path): void
    {
        if (! in_array($path, $this->viewPaths)) {
            $this->viewPaths[] = $path;
        }
    }

    public function registerAssetPath(string $path): void
    {
        if (! in_array($path, $this->assetPaths)) {
            if (! file_exists($path)) {
                $this->canCache = false;

                return;
            }

            $this->assetPaths[] = $path;
        }
    }

    public function getCacheData(): array
    {
        return [
            'viewPaths' => $this->viewPaths,
            'entryIds' => $this->entryIds,
            'termIds' => $this->termIds,
            'globalPaths' => $this->globalPaths,
            'assetPaths' => $this->assetPaths,
        ];
    }

    public function getCacheableStatusCodes(): array
    {
        if (array_key_exists('cache_response_codes', $this->configuration)) {
            return $this->configuration['cache_response_codes'];
        }

        return [
            200,
            301,
            302,
            303,
            307,
            308,
        ];
    }

    public function getIgnoreQueryStrings(): bool
    {
        if (array_key_exists('ignore_query_strings', $this->configuration)) {
            return $this->configuration['ignore_query_strings'];
        }

        return true;
    }

    public function isCacheBypassed(): bool
    {
        return $_COOKIE && array_key_exists('X-Hybrid-Cache', $_COOKIE)
            && $_COOKIE['X-Hybrid-Cache'] == 'true';
    }

    public function canHandle(): bool
    {
        if ($this->isCacheBypassed()) {
            return false;
        }

        if ($this->getIgnoreQueryStrings()) {
            if (array_key_exists('QUERY_STRING', $_SERVER)) {
                return false;
            }
        }

        // Ignore all request types except GET.
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            return false;
        }

        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return false;
        }

        if (array_key_exists('ignore_uri_patterns', $this->configuration)) {

            foreach ($this->configuration['ignore_uri_patterns'] as $pattern) {
                $pattern = str_replace('/', '\/', $pattern);
                $pattern = '/^'.$pattern.'$/';

                if (preg_match($pattern, $_SERVER['REQUEST_URI'])) {
                    return false;
                }
            }
        }

        $cacheDirectory = realpath(__DIR__.'/../../storage/hybrid-cache');

        if (! $cacheDirectory) {
            return false;
        }

        $requestUri = mb_strtolower($_SERVER['REQUEST_URI']);

        $this->cacheFileName = $cacheDirectory.'/'.sha1($requestUri).'.json';

        return file_exists($this->cacheFileName);
    }

    public function sendCachedResponse(): void
    {
        if ($this->cacheFileName && ! file_exists($this->cacheFileName)) {
            return;
        }

        $cacheContents = json_decode(file_get_contents($this->cacheFileName), true);

        if (! $cacheContents) {
            return;
        }

        if (! array_key_exists('headers', $cacheContents)) {
            return;
        }

        if (! isset($cacheContents['paths'])) {
            return;
        }

        if (! isset($cacheContents['content'])) {
            return;
        }

        if (array_key_exists('expires', $cacheContents)) {
            $expiration = $cacheContents['expires'];

            if ($expiration != null && time() > $expiration) {
                @unlink($this->cacheFileName);

                return;
            }
        }

        foreach ($cacheContents['paths'] as $path => $cachedMTime) {
            if (! file_exists($path) || filemtime($path) > $cachedMTime) {
                @unlink($this->cacheFileName);

                return;
            }
        }

        $headers = $cacheContents['headers'];
        $headers['date'] = [gmdate('D, d M Y G:i:s ').'GMT'];

        foreach ($headers as $headerName => $values) {
            if (count($values) != 1) {
                continue;
            }

            $value = $values[0];

            header($headerName.': '.$value);
        }

        echo $cacheContents['content'];
        exit;
    }
}
