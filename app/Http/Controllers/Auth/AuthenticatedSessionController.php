<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ], [], ['email' => 'email', 'password' => 'password']);

        if (! Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $request->boolean('remember'),
        )) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Rotasi session ID = proteksi session fixation
        $request->session()->regenerate();

        // Keranjang tamu digabung ke keranjang user
        $this->cart->mergeGuestCart($request->user());

        // Akun back-office (admin/staff) langsung masuk ke panel
        if ($request->user()?->isStaff()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('akun.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('success', 'Anda berhasil keluar.');
    }
}