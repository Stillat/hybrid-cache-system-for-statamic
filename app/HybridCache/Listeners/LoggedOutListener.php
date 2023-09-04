<?php

namespace App\HybridCache\Listeners;

class LoggedOutListener
{
    public function handle($event)
    {
        cookie()->queue(cookie()->forever('X-Hybrid-Cache', 'false'));
    }
}
