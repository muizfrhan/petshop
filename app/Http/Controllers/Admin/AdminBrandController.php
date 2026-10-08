<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->when($request->query('q'), fn ($q, $keyword) => $q->where('name', 'ilike', '%'.$keyword.'%'))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.brands.index', ['brands' => $brands]);
    }

    public function create(): View
    {
        return view('admin.brands.form', [
            'brand' => new Brand(['status' => 'active']),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBrand($request);

        Brand::create($this->prepare($validated));

        return redirect()->route('admin.brands.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.form', [
            'brand' => $brand,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $this->validateBrand($request, $brand);

        $brand->update($this->prepare($validated));

        return redirect()->route('admin.brands.index')->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Brand masih memiliki produk. Pindahkan produk terlebih dahulu.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand berhasil dihapus.');
    }

    private function validateBrand(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120', 'alpha_dash',
                \Illuminate\Validation\Rule::unique('brands', 'slug')->ignore($brand?->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);
    }

    private function prepare(array $data): array
    {
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }
}