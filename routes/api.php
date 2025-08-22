<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Central API (maître)
Route::get('/central', function () {
    return response()->json([
        'message' => 'Bienvenue sur la partie centrale (API)',
    ]);
});
