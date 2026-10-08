@extends('layouts.admin')

@section('title', 'Moderasi Ulasan')

@section('admin-content')
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @foreach (['all' => 'Semua', 'published' => 'Diterbitkan', 'pending' => 'Menunggu', 'hidden' => 'Disembunyikan'] as $key => $label)
            <a href="{{ route('admin.reviews.index', array_filter(['status' => $key === 'all' ? null : $key])) }}"
               @class([
                   'surface-card p-4 transition-colors',
                   'ring-2 ring-primary' => ($filters['status'] ?? null) === $key || ($key === 'all' && ! isset($filters['status'])),
               ])>
                <p class="font-label-caps text-label-caps text-outline uppercase">{{ $label }}</p>
                <p class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $counts[$key] ?? 0 }}</p>
            </a>
        @endforeach
    </div>

    <form method="GET" class="surface-card p-4 mb-5">
        @if (isset($filters['status']))
            <input type="hidden" name="status" value="{{ $filters['status'] }}">
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari komentar, produk, atau nama pelanggan..." class="form-input">
            </div>
            <select name="rating" class="form-input">
                <option value="">Semua Rating</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(($filters['rating'] ?? null) == $i)>{{ $i }} bintang</option>
                @endfor
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
        </div>
    </form>

    <div class="surface-card overflow-hidden">
        @if ($reviews->isEmpty())
            <x-empty-state title="Ulasan tidak ditemukan" description="Coba ubah filter pencarian Anda." icon="reviews" />
        @else
            <div class="divide-y divide-surface-container-low">
                @foreach ($reviews as $review)
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <x-rating :rating="$review->rating" :count="null" :showCount="false" />
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $review->status === 'published',
                                        'bg-secondary-fixed text-on-secondary-fixed-variant' => $review->status === 'pending',
                                        'bg-surface-container text-outline' => $review->status === 'hidden',
                                    ])>
                                        {{ ['published' => 'Terbit', 'pending' => 'Menunggu', 'hidden' => 'Disembunyikan'][$review->status] ?? $review->status }}
                                    </span>
                                </div>

                                <p class="font-body-sm text-body-sm text-on-surface leading-relaxed mb-3">
                                    "{{ $review->comment }}"
                                </p>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 font-label-md text-label-md text-outline">
                                    <span class="font-semibold text-on-surface">{{ $review->user?->name ?? 'Pengguna dihapus' }}</span>
                                    @if ($review->product)
                                        <a href="{{ route('admin.products.show', $review->product) }}" class="hover:text-primary transition-colors">
                                            {{ $review->product->name }}
                                        </a>
                                    @endif
                                    @if ($review->order)
                                        <span>Pesanan #{{ $review->order->order_number }}</span>
                                    @endif
                                    <span>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                <form action="{{ route('admin.reviews.status', $review) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-input !py-2.5 !w-auto min-w-[170px]"
                                            aria-label="Ubah status ulasan">
                                        <option value="pending" @selected($review->status === 'pending')>Menunggu</option>
                                        <option value="published" @selected($review->status === 'published')>Terbitkan</option>
                                        <option value="hidden" @selected($review->status === 'hidden')>Sembunyikan</option>
                                    </select>
                                    <button type="submit"
                                            class="px-4 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                                        Simpan
                                    </button>
                                </form>

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                      onsubmit="return confirm('Hapus ulasan ini? Rating produk akan dihitung ulang.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($reviews->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$reviews" />
                </div>
            @endif
        @endif
    </div>
@endsection