<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Guard area admin. Customer yang mencoba accessing /admin
     * akan menerima halaman 403 berdesain, bukan error mentah.
     *
     * Staff boleh masuk, tapi hanya untuk modul yang memang bisa diaksesnya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isStaff()) {
            return $this->deny($request, 'Anda tidak memiliki akses ke panel administrator.');
        }

        if (! $user->isAdmin() && ! $this->allowedForStaff($request)) {
            return $this->deny($request, 'Menu ini hanya tersedia untuk administrator.');
        }

        return $next($request);
    }

    /**
     * Modul yang boleh diakses staff (mengikuti menu sidebar per role).
     */
    private function allowedForStaff(Request $request): bool
    {
        $name = (string) $request->route()?->getName();

        $allowed = [
            'admin.dashboard',
            'admin.products.',
            'admin.orders.',
            'admin.services.',
            'admin.bookings.',
            'admin.reviews.',
        ];

        foreach ($allowed as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function deny(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        abort(403, $message);
    }
}