<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function notice()
    {
        return request()->user()->hasVerifiedEmail()
            ? redirect()->route('akun.index')
            : view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('akun.index')->with('success', 'Email Anda sudah terverifikasi.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('akun.index')->with('success', 'Email berhasil diverifikasi.');
    }

    public function resend()
    {
        request()->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Tautan verifikasi sudah kami kirim ulang.');
    }
}