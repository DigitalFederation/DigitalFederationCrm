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
        //
    ];

    protected function tokensMatch($request)
    {

        if (parent::tokensMatch($request)) {
            return true;
        }

        return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');

    }
}
