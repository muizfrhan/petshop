<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('account.index', [
            'user' => $user,
            'orders' => $user->orders()->withCount('items')->take(5)->get(),
            'wishlistCount' => $user->wishlists()->count(),
            'bookings' => $user->bookings()->with('service')->take(5)->get(),
            'unreadNotifications' => $user->unreadNotifications()->take(5)->get(),
            'stats' => [
                'total_orders' => $user->orders()->count(),
                'completed_orders' => $user->orders()->where('status', 'completed')->count(),
                'total_reviews' => $user->reviews()->count(),
                'total_spent' => (int) $user->orders()
                    ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                    ->sum('total'),
            ],
        ]);
    }

    public function edit(Request $request)
    {
        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\s]+$/', 'min:8', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [], [
            'name' => 'nama',
            'avatar' => 'foto profil',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Email hanya diubah setelah diverifikasi ulang (di-nonaktifkan di sini
        // agar tidak membingungkan user lokal).
        unset($validated['email']);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [], [
            'current_password' => 'password saat ini',
            'password' => 'password baru',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ], [], ['password' => 'password']);

        $user = $request->user();

        if ($user->isAdmin()) {
            return back()->with('error', 'Akun administrator tidak dapat dihapus.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('success', 'Akun Anda telah dihapus.');
    }

    public function notifications(Request $request)
    {
        $user = $request->user();

        $user->unreadNotifications->markAsRead();

        return view('account.notifications', [
            'notifications' => $user->notifications()->paginate(15),
        ]);
    }
}