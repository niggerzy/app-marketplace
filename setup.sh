#!/bin/bash

# FASE 2: CORE FEATURES - Middleware

cat > ~/marketplace/app/Http/Middleware/Admin.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
EOF

cat > ~/marketplace/app/Http/Middleware/Customer.php << 'EOF'
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
EOF

echo "✅ Middleware created"
