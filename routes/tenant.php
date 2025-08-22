<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::get('/hello', fn() => response()->json(['tenant_id' => tenant('id')]));
    Route::apiResource('/posts', PostController::class);

    // Ceci est juste un example de comment utiliser le middleware Sanctum dans une route tenant
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/route_example_token', PostController::class);
    });
});
