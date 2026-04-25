<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Customer
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isCustomer()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
