<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [], ['email' => 'email']);

        $status = Password::sendResetLink($request->only('email'));

        // Selalu pesan sukses agar tidak membocorkan email yang terdaftar.
        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Tautan reset password sudah kami kirim ke email Anda.')
            : back()->with('success', 'Jika email tersebut terdaftar, kami sudah mengirim tautan reset password.');
    }

    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8', 'letters', 'numbers'],
        ], [], ['email' => 'email', 'password' => 'password']);

        $status = Password::reset(
            $validated,
            function ($user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => \Illuminate\Support\Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan masuk.')
            : back()->withErrors(['email' => 'Tautan reset password tidak valid atau sudah kedaluwarsa.']);
    }
}