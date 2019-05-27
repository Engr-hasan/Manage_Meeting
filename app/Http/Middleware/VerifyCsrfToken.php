<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'http://dev-ocplbase.eserve.org.bd/',
        '/api/action/new-job',
        '/api/new-job',
        'https://dev-app.eserve.org.bd/api/new-job',
        'https://dev-app.eserve.org.bd/api/action/new-job',
    ];
}
