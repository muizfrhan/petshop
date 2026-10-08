<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
        ], [], ['kategori' => 'kategori']);

        $articles = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->search($validated['q'] ?? null)
            ->when($validated['kategori'] ?? null, fn ($q, $slug) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $slug),
            ))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('articles.index', [
            'articles' => $articles,
            'categories' => ArticleCategory::withCount('articles')->orderBy('name')->get(),
            'filters' => $validated,
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Tambah views tanpa menyentuh updated_at
        $article->increment('views');

        $related = Article::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->when($article->article_category_id, fn ($q) => $q->where('article_category_id', $article->article_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}