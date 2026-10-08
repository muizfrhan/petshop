<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\s]+$/', 'min:8', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [], [
            'name' => 'nama',
            'email' => 'email',
            'phone' => 'nomor telepon',
        ]);

        $user = DB::transaction(fn () => User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            // Casting 'hashed' pada model akan meng-hash password di sini.
            'password' => $validated['password'],
            'role' => User::ROLE_CUSTOMER,
            'status' => User::STATUS_ACTIVE,
        ]));

        $user->notify(new WelcomeNotification());

        Auth::login($user);
        $request->session()->regenerate();

        $this->cart->mergeGuestCart($user);

        return redirect()->route('akun.index')
            ->with('success', 'Selamat datang di PETCARE HOUSE, '.$user->name.'!');
    }
}