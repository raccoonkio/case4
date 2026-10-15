<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->session()->has('user_id')) {
            return redirect()->route('login')->with('status', 'Войдите, чтобы открыть этот раздел.');
        }

        if ($request->session()->get('user_role') !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
