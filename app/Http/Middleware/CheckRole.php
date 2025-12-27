<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        // Normalize roles (accept comma-separated strings and different casing/whitespace)
        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $allowed[] = strtolower($part);
                }
            }
        }

        $userRole = strtolower(trim((string) ($user->role ?? '')));

        if (!in_array($userRole, $allowed, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
