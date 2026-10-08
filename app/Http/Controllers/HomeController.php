<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // Data etalase di-cache singkat supaya tidak query ulang tiap kunjungan.
        $featured = Cache::remember('home.featured_products', 300, fn () => Product::query()
            ->active()
            ->featured()
            ->with(['primaryImage', 'category', 'brand'])
            ->latest()
            ->take(8)
            ->get());

        $categories = Cache::remember('home.categories', 600, fn () => Category::query()
            ->active()
            ->ordered()
            ->withCount(['products' => fn ($q) => $q->where('status', 'active')])
            ->take(6)
            ->get());

        $services = Cache::remember('home.services', 600, fn () => Service::query()
            ->active()
            ->ordered()
            ->take(6)
            ->get());

        $articles = Cache::remember('home.articles', 600, fn () => Article::query()
            ->published()
            ->with('category', 'author')
            ->latest('published_at')
            ->take(3)
            ->get());

        // Testimonial diambil dari ULASAN nyata (sudah lewat moderasi),
        // bukan teks dummy.
        $testimonials = Cache::remember('home.testimonials', 600, fn () => \App\Models\Review::query()
            ->published()
            ->whereNotNull('comment')
            ->with(['user', 'product'])
            ->whereHas('order', fn ($q) => $q->where('status', Order::STATUS_COMPLETED))
            ->latest()
            ->take(3)
            ->get());

        return view('home', [
            'featuredProducts' => $featured,
            'categories' => $categories,
            'services' => $services,
            'articles' => $articles,
            'testimonials' => $testimonials,
            'stats' => [
                'products' => Product::active()->count(),
                'services' => Service::active()->count(),
                'categories' => Category::active()->count(),
                'bookings' => ServiceBooking::count(),
            ],
        ]);
    }
}