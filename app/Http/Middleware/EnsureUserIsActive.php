<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Menolak user yang statusnya nonaktif, meski passwordnya benar.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isActive()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda dinonaktifkan. Hubungi administrator.'], 403);
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.']);
        }

        return $next($request);
    }
}