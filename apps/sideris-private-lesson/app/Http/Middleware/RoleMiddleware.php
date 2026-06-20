<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user['role'] !== $role) {
            // Redirect ke halaman yang sesuai dengan role mereka
            return match ($user['role']) {
                'admin' => redirect()->route('admin'),
                default => redirect()->route('student'),
            };
        }

        return $next($request);
    }
}
