<?php

namespace App\HybridCache\Listeners;

class LoginSuccessListener
{
    public function handle($event)
    {
        cookie()->queue(cookie()->forever('X-Hybrid-Cache', 'true'));
    }
}
