<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()
            ->withCount('bookings')
            ->when($request->query('q'), fn ($q, $keyword) => $q->where('name', 'ilike', '%'.$keyword.'%'))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', ['services' => $services]);
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service' => new Service(['status' => 'active', 'price' => 0, 'duration_minutes' => 60, 'sort_order' => 0]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = Service::create($this->prepare($request));

        return redirect()->route('admin.services.edit', $service)
            ->with('success', "Layanan {$service->name} berhasil ditambahkan.");
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', [
            'service' => $service,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->prepare($request));

        return redirect()->route('admin.services.edit', $service)
            ->with('success', "Layanan {$service->name} berhasil diperbarui.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan {$service->name} berhasil dihapus.");
    }

    private function prepare(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:2000'],
            'includes' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
            'icon' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'featured' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        return $data;
    }
}