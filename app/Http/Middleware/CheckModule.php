<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * Abort 404 if the given module is disabled in Settings.
     * Usage: Route::middleware('check.module:trading')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $module  The module key to check (e.g. 'trading', 'nft', 'loan')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $module)
    {
        $settings = Settings::find(1);
        $modules = $settings->modules ?? [];

        if (empty($modules[$module])) {
            abort(404);
        }

        return $next($request);
    }
}
