<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCouponController extends Controller
{
    public function index(Request $request): View
    {
        $coupons = Coupon::query()
            ->when($request->query('q'), fn ($q, $keyword) => $q->where(function ($sub) use ($keyword) {
                $sub->where('code', 'ilike', '%'.$keyword.'%')->orWhere('name', 'ilike', '%'.$keyword.'%');
            }))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.coupons.index', ['coupons' => $coupons]);
    }

    public function create(): View
    {
        return view('admin.coupons.form', [
            'coupon' => new Coupon(['status' => 'active', 'type' => 'percent', 'value' => 10]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Coupon::create($this->prepare($request));

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dibuat.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.form', [
            'coupon' => $coupon,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($this->prepare($request, $coupon));

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil diperbarui.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus.');
    }

    private function prepare(Request $request, ?Coupon $coupon = null): array
    {
        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:40',
                \Illuminate\Validation\Rule::unique('coupons', 'code')->ignore($coupon?->id)],
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1',
                \Illuminate\Validation\Rule::when(
                    $request->input('type') === 'percent',
                    ['max:100'],
                    ['max:1000000000'],
                )],
            'min_purchase' => ['required', 'integer', 'min:0'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ], [], [
            'min_purchase' => 'minimum belanja',
            'starts_at' => 'tanggal mulai',
            'expires_at' => 'tanggal berakhir',
        ]);

        if (blank($data['code'] ?? null)) {
            $data['code'] = Str::upper(Str::random(8));
        } else {
            $data['code'] = Str::upper($data['code']);
        }

        return $data;
    }
}