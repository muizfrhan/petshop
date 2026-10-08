<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletter_subscribers,email'],
        ], [], ['email' => 'email']);

        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'subscribed_at' => now(),
        ]);

        return back()->with('success', 'Berhasil berlangganan! Voucher Rp 50.000 akan kami kirim ke email Anda.');
    }
}