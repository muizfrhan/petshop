<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($request->query('q'), fn ($q, $keyword) => $q->where('name', 'ilike', '%'.$keyword.'%'))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['status' => 'active', 'sort_order' => 0]),
            'mode' => 'create',
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $this->validatedWithImage($request);

        $category = Category::create($data);

        return redirect()->route('admin.categories.edit', $category)
            ->with('success', "Kategori {$category->name} berhasil ditambahkan.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
            'mode' => 'edit',
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $this->validatedWithImage($request);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        }

        $category->update($data);

        return redirect()->route('admin.categories.edit', $category)
            ->with('success', "Kategori {$category->name} berhasil diperbarui.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori masih memiliki produk. Pindahkan produk terlebih dahulu.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori {$category->name} berhasil dihapus.");
    }

    private function validatedWithImage(Request $request): array
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        unset($data['image_removal']);

        return $data;
    }
}