<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
            EnsureUserIsActive::class,
        ]);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'active' => EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*
         | Pesan error dalam Bahasa Indonesia. Halaman error (403/404/419/500)
         | memakai layout PETCARE HOUSE, bukan halaman default Laravel.
         */
        $exceptions->getStatusMessage = function (int $status): string {
            return match ($status) {
                403 => 'Akses Ditolak',
                404 => 'Halaman Tidak Ditemukan',
                405 => 'Metode Tidak Diizinkan',
                419 => 'Sesi Kedaluwarsa',
                422 => 'Data Tidak Valid',
                429 => 'Terlalu Banyak Permintaan',
                500 => 'Terjadi Kesalahan',
                503 => 'Layanan Sedang Maintenance',
                default => 'Terjadi Kesalahan',
            };
        };

        // Jangan pernah bocorkan exception ke pengguna biasa.
        $exceptions->dontReport([
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Session\TokenMismatchException::class,
            \Illuminate\Validation\ValidationException::class,
        ]);

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return response()->json([
                'message' => $status >= 500
                    ? 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.'
                    : ($e->getMessage() ?: 'Permintaan tidak dapat diproses.'),
            ], $status);
        });
    })->create();