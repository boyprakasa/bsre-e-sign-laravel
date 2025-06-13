<?php

use Illuminate\Support\Facades\Route;

// Prefix route agar tidak bentrok dengan route utama
Route::prefix('bsre')->group(function () {
    Route::get('/test', function () {
        return 'Halo dari Paket BSR-e! URL API: ' . config('bsre.host');
    });
});
