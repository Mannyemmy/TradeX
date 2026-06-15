<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\License;
use Illuminate\Support\Facades\Redirect;

class CheckLicense
{
    /**
     * Middleware neutralized — previous implementation silently registered
     * server domains to a License table and redirected when limit exceeded.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}

