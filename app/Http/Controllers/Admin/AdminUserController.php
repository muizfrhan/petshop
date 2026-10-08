<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'in:admin,staff,customer'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ], [], ['role' => 'peran']);

        $users = User::query()
            ->when($validated['q'] ?? null, fn ($q, $keyword) => $q->where(function ($sub) use ($keyword) {
                $sub->where('name', 'ilike', '%'.$keyword.'%')
                    ->orWhere('email', 'ilike', '%'.$keyword.'%')
                    ->orWhere('phone', 'ilike', '%'.$keyword.'%');
            }))
            ->when($validated['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->withCount('orders')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $validated,
            'counts' => [
                'all' => User::count(),
                'customer' => User::where('role', 'customer')->count(),
                'admin' => User::where('role', 'admin')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'inactive' => User::where('status', 'inactive')->count(),
            ],
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\s]+$/', 'min:8', 'max:20'],
            'role' => ['required', 'string', 'in:admin,staff,customer'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [], [
            'name' => 'nama',
            'role' => 'peran',
            'password' => 'password',
        ]);

        // Admin tidak boleh mengunci dirinya sendiri di luar sistem.
        if ($user->id === $request->user()->id && $validated['status'] === 'inactive') {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        if (isset($validated['password']) && $validated['password'] !== '') {
            $validated['password'] = $validated['password'];
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $user->update([
            'status' => $user->isActive() ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status akun '.$user->name.' diubah.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Minimal harus ada satu akun administrator.');
        }

        if ($user->orders()->whereIn('status', ['pending', 'paid', 'processing', 'shipped'])->exists()) {
            return back()->with('error', 'Pelanggan masih memiliki pesanan aktif sehingga akun tidak bisa dihapus. Nonaktifkan saja.');
        }

        $user->delete();

        return back()->with('success', 'Akun '.$user->name.' berhasil dihapus.');
    }
}