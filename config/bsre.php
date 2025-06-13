<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi API BSR-e
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menyimpan semua kredensial dan pengaturan
    | yang berhubungan dengan API Tanda Tangan Elektronik dari BSSN-BSR-e.
    |
    */

    'host' => env('BSRE_HOST'),
    'username' => env('BSRE_USERNAME'),
    'password' => env('BSRE_PASSWORD'),

    // Timeout untuk request dalam detik
    'timeout' => 30,
];
