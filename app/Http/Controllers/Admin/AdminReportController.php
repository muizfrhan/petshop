<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ], [], ['from' => 'tanggal awal', 'to' => 'tanggal akhir']);

        $from = isset($validated['from']) ? Carbon::parse($validated['from'])->startOfDay() : now()->subDays(29)->startOfDay();
        $to = isset($validated['to']) ? Carbon::parse($validated['to'])->endOfDay() : now()->endOfDay();

        $paidStatuses = ['paid', 'processing', 'shipped', 'completed'];

        $summary = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw("SUM(CASE WHEN status IN ('paid','processing','shipped','completed') THEN 1 ELSE 0 END) as paid_orders")
            ->selectRaw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders")
            ->selectRaw("SUM(CASE WHEN status IN ('paid','processing','shipped','completed') THEN total ELSE 0 END) as revenue")
            ->selectRaw("AVG(CASE WHEN status IN ('paid','processing','shipped','completed') THEN total END) as avg_order")
            ->first();

        // Penjualan harian
        $daily = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', $paidStatuses)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Penjualan mingguan
        $weekly = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', $paidStatuses)
            ->selectRaw("DATE_TRUNC('week', created_at) as week, COUNT(*) as orders, SUM(total) as revenue")
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Penjualan bulanan
        $monthly = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', $paidStatuses)
            ->selectRaw("TO_CHAR(DATE_TRUNC('month', created_at), 'YYYY-MM') as month, COUNT(*) as orders, SUM(total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.status', $paidStatuses)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('products.name, products.sku, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $topCustomers = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', $paidStatuses)
            ->whereNotNull('user_id')
            ->selectRaw('user_id, COUNT(*) as orders, SUM(total) as total_spent')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $customer = \App\Models\User::find($row->user_id);

                return [
                    'name' => $customer?->name ?? 'Pelanggan dihapus',
                    'email' => $customer?->email ?? '-',
                    'orders' => (int) $row->orders,
                    'total_spent' => (int) $row->total_spent,
                ];
            });

        return view('admin.reports.index', [
            'filters' => $validated,
            'from' => $from,
            'to' => $to,
            'summary' => $summary,
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'topProducts' => $topProducts,
            'topCustomers' => $topCustomers,
        ]);
    }

    public function export(Request $request, string $type): StreamedResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ], [], ['from' => 'tanggal awal', 'to' => 'tanggal akhir']);

        $from = isset($validated['from']) ? Carbon::parse($validated['from'])->startOfDay() : now()->subDays(29)->startOfDay();
        $to = isset($validated['to']) ? Carbon::parse($validated['to'])->endOfDay() : now()->endOfDay();

        $filename = "laporan-{$type}-".now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($type, $from, $to) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel membaca UTF-8 (huruf Indonesia) dengan benar
            fwrite($handle, "\xEF\xBB\xBF");

            match ($type) {
                'produk-terlaris' => $this->exportTopProducts($handle, $from, $to),
                'pelanggan' => $this->exportTopCustomers($handle, $from, $to),
                'pesanan' => $this->exportOrders($handle, $from, $to),
                default => $this->exportDailySales($handle, $from, $to),
            };

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportDailySales($handle, $from, $to): void
    {
        fputcsv($handle, ['Tanggal', 'Jumlah Pesanan', 'Total Pendapatan (Rp)']);

        Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->each(fn ($row) => fputcsv($handle, [$row->day, $row->orders, $row->revenue]));
    }

    private function exportTopProducts($handle, $from, $to): void
    {
        fputcsv($handle, ['Produk', 'SKU', 'Qty Terjual', 'Pendapatan (Rp)']);

        DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('products.name, products.sku, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('qty')
            ->get()
            ->each(fn ($row) => fputcsv($handle, [$row->name, $row->sku, $row->qty, $row->revenue]));
    }

    private function exportTopCustomers($handle, $from, $to): void
    {
        fputcsv($handle, ['Nama Pelanggan', 'Email', 'Jumlah Pesanan', 'Total Belanja (Rp)']);

        Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereNotNull('user_id')
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($orders) {
                $first = $orders->first();

                return [
                    'name' => $first->user?->name ?? 'Pelanggan dihapus',
                    'email' => $first->user?->email ?? '-',
                    'orders' => $orders->count(),
                    'total' => $orders->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->take(50)
            ->each(fn ($row) => fputcsv($handle, [$row['name'], $row['email'], $row['orders'], $row['total']]));
    }

    private function exportOrders($handle, $from, $to): void
    {
        fputcsv($handle, ['No. Pesanan', 'Tanggal', 'Pelanggan', 'Status', 'Total (Rp)', 'Metode Bayar']);

        Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->get()
            ->each(fn ($o) => fputcsv($handle, [
                $o->order_number,
                $o->created_at->format('Y-m-d H:i'),
                $o->customer_name,
                $o->statusLabel(),
                $o->total,
                config('petshop.payment_methods')[$o->payment_method] ?? $o->payment_method,
            ]));
    }
}