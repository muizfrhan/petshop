@extends('layouts.admin')

@section('title', 'Laporan & Statistik')
@section('subtitle', $from->format('d/m/Y') . ' — ' . $to->format('d/m/Y'))

@section('admin-content')
    @php
        $exportUrl = fn (string $type) => route('admin.reports.export', array_merge(
            [$type],
            array_filter(['from' => $filters['from'] ?? null, 'to' => $filters['to'] ?? null]),
        ));
    @endphp

    <form method="GET" class="surface-card p-4 mb-6">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label" for="from">Dari Tanggal</label>
                <input type="date" id="from" name="from" value="{{ $filters['from'] ?? $from->format('Y-m-d') }}"
                       class="form-input !w-auto" max="{{ $to->format('Y-m-d') }}">
            </div>
            <div>
                <label class="form-label" for="to">Sampai Tanggal</label>
                <input type="date" id="to" name="to" value="{{ $filters['to'] ?? $to->format('Y-m-d') }}"
                       class="form-input !w-auto">
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors">
                Tampilkan
            </button>
            <div class="ml-auto flex flex-wrap items-center gap-2">
                <span class="font-label-md text-label-md text-outline mr-1">Ekspor CSV:</span>
                <a href="{{ $exportUrl('penjualan') }}"
                   class="px-4 py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span> Penjualan
                </a>
                <a href="{{ $exportUrl('produk-terlaris') }}"
                   class="px-4 py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span> Produk
                </a>
                <a href="{{ $exportUrl('pelanggan') }}"
                   class="px-4 py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span> Pelanggan
                </a>
                <a href="{{ $exportUrl('pesanan') }}"
                   class="px-4 py-2.5 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span> Pesanan
                </a>
            </div>
        </div>
    </form>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="surface-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                </div>
                <p class="font-label-caps text-label-caps text-outline uppercase">Total Pesanan</p>
            </div>
            <p class="font-headline text-headline text-on-surface font-bold">{{ $summary->total_orders ?? 0 }}</p>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-tertiary-fixed flex items-center justify-center text-tertiary-container">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                </div>
                <p class="font-label-caps text-label-caps text-outline uppercase">Pendapatan</p>
            </div>
            <p class="font-headline text-headline text-primary font-bold">Rp {{ number_format($summary->revenue ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                </div>
                <p class="font-label-caps text-label-caps text-outline uppercase">Pesanan Dibayar</p>
            </div>
            <p class="font-headline text-headline text-on-surface font-bold">{{ (int) ($summary->paid_orders ?? 0) }}</p>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-error-container flex items-center justify-center text-on-error-container">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                </div>
                <p class="font-label-caps text-label-caps text-outline uppercase">Dibatalkan</p>
            </div>
            <p class="font-headline text-headline text-on-surface font-bold">{{ (int) ($summary->cancelled_orders ?? 0) }}</p>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                </div>
                <p class="font-label-caps text-label-caps text-outline uppercase">Rata-rata Pesanan</p>
            </div>
            <p class="font-headline text-headline text-on-surface font-bold">
                Rp {{ number_format((int) round($summary->avg_order ?? 0), 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Penjualan harian --}}
        <div class="surface-card p-6 lg:col-span-2">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Penjualan Harian</h2>

            @if ($daily->isEmpty())
                <x-empty-state title="Belum ada penjualan" description="Tidak ada pesanan berbayar pada rentang tanggal ini."
                               icon="bar_chart" />
            @else
                @php
                    $maxRevenue = max(1, $daily->max('revenue'));
                @endphp
                <div class="space-y-3">
                    @foreach ($daily as $row)
                        <div class="flex items-center gap-4">
                            <span class="w-24 shrink-0 font-label-md text-label-md text-outline">
                                {{ \Illuminate\Support\Carbon::parse($row->day)->format('d/m') }}
                            </span>
                            <div class="flex-1 h-7 bg-surface-container-low rounded-lg overflow-hidden">
                                <div class="h-full bg-primary rounded-lg transition-all"
                                     style="width: {{ max(2, round($row->revenue / $maxRevenue * 100)) }}%"></div>
                            </div>
                            <span class="w-32 shrink-0 text-right font-label-md text-label-md font-semibold text-on-surface">
                                Rp {{ number_format($row->revenue, 0, ',', '.') }}
                            </span>
                            <span class="w-14 shrink-0 text-right font-label-md text-label-md text-outline">
                                {{ $row->orders }} trx
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Periode --}}
        <div class="surface-card p-6">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Rekap Periode</h2>

            <p class="font-label-caps text-label-caps text-outline uppercase mb-3">Mingguan</p>
            @if ($weekly->isEmpty())
                <p class="font-body-sm text-body-sm text-outline mb-5">Belum ada data.</p>
            @else
                <div class="space-y-2 mb-5">
                    @foreach ($weekly as $row)
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-body-sm text-body-sm text-on-surface">
                                Minggu {{ \Illuminate\Support\Carbon::parse($row->week)->format('d/m') }}
                            </span>
                            <span class="font-label-md text-label-md font-semibold text-primary">
                                Rp {{ number_format($row->revenue, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <p class="font-label-caps text-label-caps text-outline uppercase mb-3">Bulanan</p>
            @if ($monthly->isEmpty())
                <p class="font-body-sm text-body-sm text-outline">Belum ada data.</p>
            @else
                <div class="space-y-2">
                    @foreach ($monthly as $row)
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-body-sm text-body-sm text-on-surface">{{ $row->month }}</span>
                            <span class="text-right">
                                <span class="font-label-md text-label-md font-semibold text-primary">
                                    Rp {{ number_format($row->revenue, 0, ',', '.') }}
                                </span>
                                <span class="font-label-md text-label-md text-outline"> · {{ $row->orders }} trx</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Produk terlaris --}}
        <div class="surface-card overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-container-low flex items-center justify-between">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Produk Terlaris</h2>
                <a href="{{ $exportUrl('produk-terlaris') }}"
                   class="font-label-md text-label-md text-primary hover:underline inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">download</span> CSV
                </a>
            </div>

            @if ($topProducts->isEmpty())
                <x-empty-state title="Belum ada data" description="Belum ada produk terjual pada rentang ini." icon="inventory_2" />
            @else
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th class="text-right">Terjual</th>
                                <th class="text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $i => $row)
                                <tr>
                                    <td>
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-primary-fixed text-primary font-label-md text-label-md font-bold">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $row->name }}</p>
                                        <p class="font-mono font-label-md text-label-md text-outline">{{ $row->sku }}</p>
                                    </td>
                                    <td class="text-right font-label-md text-label-md font-semibold text-on-surface">{{ $row->qty }}</td>
                                    <td class="text-right font-label-md text-label-md font-semibold text-primary">
                                        Rp {{ number_format($row->revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Pelanggan teratas --}}
        <div class="surface-card overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-container-low flex items-center justify-between">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pelanggan Teratas</h2>
                <a href="{{ $exportUrl('pelanggan') }}"
                   class="font-label-md text-label-md text-primary hover:underline inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">download</span> CSV
                </a>
            </div>

            @if ($topCustomers->isEmpty())
                <x-empty-state title="Belum ada data" description="Belum ada pesanan dari pelanggan terdaftar." icon="group" />
            @else
                <div class="table-shell">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th class="text-right">Pesanan</th>
                                <th class="text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topCustomers as $i => $row)
                                <tr>
                                    <td>
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-primary-fixed text-primary font-label-md text-label-md font-bold">
                                            {{ $i + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="font-body-sm text-body-sm text-on-surface font-semibold">{{ $row['name'] }}</p>
                                        <p class="font-label-md text-label-md text-outline">{{ $row['email'] }}</p>
                                    </td>
                                    <td class="text-right font-label-md text-label-md font-semibold text-on-surface">{{ $row['orders'] }}</td>
                                    <td class="text-right font-label-md text-label-md font-semibold text-primary">
                                        Rp {{ number_format($row['total_spent'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection