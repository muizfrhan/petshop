<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = today();

        // Revenue hanya dari pesanan yang benar-benar dibayar / diproses
        $revenuePaid = (int) Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total');

        $revenueToday = (int) Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereDate('created_at', $today)
            ->sum('total');

        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => $revenuePaid,
            'revenue_today' => $revenueToday,
            'order_pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'order_processing' => Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_PROCESSING])->count(),
            'bookings_today' => ServiceBooking::today()->count(),
            'low_stock' => Product::where('stock', '<=', config('petshop.low_stock_threshold', 10))
                ->where('status', 'active')
                ->count(),
            'pending_reviews' => Review::where('status', Review::STATUS_PENDING)->count(),
            'active_services' => Service::active()->count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
        ];

        // Grafik 14 hari terakhir (sales chart)
        $salesSeries = Order::query()
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->selectRaw("DATE(created_at) as day, COUNT(*) as orders, SUM(total) as revenue")
            ->groupBy('day')
            ->pluck('revenue', 'day');

        $ordersSeries = Order::query()
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->groupBy('day')
            ->pluck('total', 'day');

        $salesChart = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $salesChart[] = [
                'date' => $date,
                'label' => Carbon::parse($date)->translatedFormat('d M'),
                'revenue' => (int) ($salesSeries[$date] ?? 0),
                'orders' => (int) ($ordersSeries[$date] ?? 0),
            ];
        }

        $topProducts = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->selectRaw('order_items.product_name, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $todayBookings = ServiceBooking::with(['service', 'user'])
            ->today()
            ->orderBy('booking_time')
            ->take(6)
            ->get();

        $lowStockProducts = Product::with('primaryImage')
            ->where('stock', '<=', config('petshop.low_stock_threshold', 10))
            ->where('status', 'active')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'salesChart' => $salesChart,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'todayBookings' => $todayBookings,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}