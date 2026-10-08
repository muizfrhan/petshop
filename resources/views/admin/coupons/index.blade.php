@extends('layouts.admin')

@section('title', 'Kelola Kupon')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}"
                       placeholder="Cari kode atau nama kupon..." class="form-input">
            </div>
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.coupons.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $coupons->total() }} kupon</span>
            <a href="{{ route('admin.coupons.create') }}"
               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Buat Kupon
            </a>
        </div>
    </form>

    <div class="surface-card overflow-hidden">
        @if ($coupons->isEmpty())
            <x-empty-state title="Kupon tidak ditemukan" description="Buat kupon diskon pertama Anda untuk pelanggan."
                           icon="local_offer" :action="route('admin.coupons.create')" action-label="Buat Kupon" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Kupon</th>
                            <th>Diskon</th>
                            <th>Minimum Belanja</th>
                            <th>Penggunaan</th>
                            <th>Masa Berlaku</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>
                                    <p class="font-mono font-label-lg text-label-lg font-bold text-primary">{{ $coupon->code }}</p>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $coupon->name }}</p>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-bold text-on-surface">
                                        {{ $coupon->type === 'percent' ? $coupon->value.'%' : 'Rp '.number_format($coupon->value, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">
                                        {{ $coupon->min_purchase > 0 ? 'Rp '.number_format($coupon->min_purchase, 0, ',', '.') : 'Tanpa minimum' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">
                                        {{ $coupon->used_count }}{{ $coupon->quota ? ' / '.$coupon->quota : '' }}
                                    </span>
                                </td>
                                <td>
                                    <p class="font-label-md text-label-md text-outline">
                                        {{ $coupon->starts_at?->format('d/m/Y') ?? 'Mulai sekarang' }}
                                        s.d.
                                        {{ $coupon->expires_at?->format('d/m/Y') ?? 'Tanpa batas' }}
                                    </p>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $coupon->status === 'active',
                                        'bg-surface-container text-outline' => $coupon->status !== 'active',
                                    ])>{{ $coupon->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                              onsubmit="return confirm('Hapus kupon {{ $coupon->code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($coupons->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$coupons" />
                </div>
            @endif
        @endif
    </div>
@endsection