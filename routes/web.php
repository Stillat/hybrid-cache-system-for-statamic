<?php

use Illuminate\Support\Facades\Route;

Route::get('test', [\App\Http\Controllers\TestController::class, 'index']);

Route::get('test2', function () {
    return 'Hello, world!';
});
