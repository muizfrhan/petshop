<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:draft,published'],
            'kategori' => ['nullable', 'integer', 'exists:article_categories,id'],
        ], [], ['kategori' => 'kategori']);

        $articles = Article::query()
            ->with(['category', 'author'])
            ->search($validated['q'] ?? null)
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($validated['kategori'] ?? null, fn ($q, $id) => $q->where('article_category_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'filters' => $validated,
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.form', [
            'article' => new Article(['status' => 'draft']),
            'categories' => ArticleCategory::orderBy('name')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->prepare($request, $request->user()->id));

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', [
            'article' => $article,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->prepare($request, $article->author_id));

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    private function prepare(Request $request, ?int $authorId): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', 'alpha_dash'],
            'excerpt' => ['required', 'string', 'min:20', 'max:400'],
            'content' => ['required', 'string', 'min:50'],
            'article_category_id' => ['nullable', 'integer', 'exists:article_categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ], [], [
            'article_category_id' => 'kategori artikel',
            'published_at' => 'tanggal publikasi',
        ]);

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $data['author_id'] = $authorId;

        // Tanggal publikasi diisi otomatis saat pertama kali di-publish
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}