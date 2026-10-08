<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::active()->ordered()->withCount('bookings')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('services.show', [
            'service' => $service,
            'others' => Service::active()
                ->where('id', '!=', $service->id)
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }
}