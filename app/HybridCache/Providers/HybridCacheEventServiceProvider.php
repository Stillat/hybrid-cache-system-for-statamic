<?php

namespace App\HybridCache\Providers;

use App\HybridCache\Listeners\EntryCreatedListener;
use App\HybridCache\Listeners\EntryDeletedListener;
use App\HybridCache\Listeners\EntrySavedListener;
use App\HybridCache\Listeners\LoggedOutListener;
use App\HybridCache\Listeners\LoginSuccessListener;
use App\HybridCache\Listeners\ResponsePreparedListener;
use App\HybridCache\Listeners\TaxonomyCreatedListener;
use App\HybridCache\Listeners\TaxonomyDeletedListener;
use App\HybridCache\Listeners\TaxonomySavedListener;
use App\HybridCache\Listeners\TermCreatedListener;
use App\HybridCache\Listeners\TermDeletedListener;
use App\HybridCache\Listeners\TermSavedListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Routing\Events\ResponsePrepared;
use Statamic\Events\EntryCreated;
use Statamic\Events\EntryDeleted;
use Statamic\Events\EntrySaved;
use Statamic\Events\TaxonomyCreated;
use Statamic\Events\TaxonomyDeleted;
use Statamic\Events\TaxonomySaved;
use Statamic\Events\TermCreated;
use Statamic\Events\TermDeleted;
use Statamic\Events\TermSaved;

class HybridCacheEventServiceProvider extends EventServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ResponsePrepared::class => [
            ResponsePreparedListener::class,
        ],
        Login::class => [
            LoginSuccessListener::class,
        ],
        Logout::class => [
            LoggedOutListener::class,
        ],
        TermSaved::class => [
            TermSavedListener::class,
        ],
        TermCreated::class => [
            TermCreatedListener::class,
        ],
        TermDeleted::class => [
            TermDeletedListener::class,
        ],
        TaxonomyCreated::class => [
            TaxonomyCreatedListener::class,
        ],
        TaxonomySaved::class => [
            TaxonomySavedListener::class,
        ],
        TaxonomyDeleted::class => [
            TaxonomyDeletedListener::class,
        ],
        EntrySaved::class => [
            EntrySavedListener::class,
        ],
        EntryCreated::class => [
            EntryCreatedListener::class,
        ],
        EntryDeleted::class => [
            EntryDeletedListener::class,
        ],
    ];
}
