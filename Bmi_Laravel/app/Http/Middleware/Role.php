<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class Role {
    public function handle(Request $request, Closure $next, $role) {
        if ($request->user()->role !== $role) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Unauthorized'], 403)
                : redirect()->route('login')->with('error', 'Unauthorized');
        }
        return $next($request);
    }
}
