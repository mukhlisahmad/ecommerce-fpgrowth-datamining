<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'admin/login',  // Menambahkan pengecualian untuk rute login admin
        // Tambahkan rute lain yang ingin Anda kecualikan, misalnya:
        // 'admin/another-route',
    ];
}
