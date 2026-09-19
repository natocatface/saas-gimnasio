<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Socios van a su propio login; el resto al login del staff.
        if ($request->is('socio*')) {
            return route('portal.login');
        }

        return route('login');
    }
}
