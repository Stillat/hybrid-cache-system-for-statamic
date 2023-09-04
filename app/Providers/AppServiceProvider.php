<?php

namespace App\Providers;

use App\Data\Asset;
use App\Data\Entry;
use App\Data\Term;
use App\Data\Variables;
use App\TorchlightClient;
use Illuminate\Support\ServiceProvider;
use Statamic\Contracts\Assets\Asset as AssetContract;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Contracts\Globals\Variables as VariablesContract;
use Statamic\Contracts\Taxonomies\Term as TermContract;
use Statamic\Statamic;
use Torchlight\Torchlight;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AssetContract::class, Asset::class);
        $this->app->bind(EntryContract::class, Entry::class);
        $this->app->bind(TermContract::class, Term::class);
        $this->app->bind(VariablesContract::class, Variables::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Statamic::vite('app', [
        //     'resources/js/cp.js',
        //     'resources/css/cp.css',
        // ]);

        Torchlight::setClient(new TorchlightClient());
    }
}
