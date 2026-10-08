@extends('layouts.admin')

@section('title', 'Kelola Artikel')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari judul atau isi artikel..." class="form-input">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <select name="kategori" class="form-input">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['kategori'] ?? null) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="published" @selected(($filters['status'] ?? '') === 'published')>Terbit</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (array_filter($filters))
                <a href="{{ route('admin.articles.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $articles->total() }} artikel</span>
            <a href="{{ route('admin.articles.create') }}"
               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Tulis Artikel
            </a>
        </div>
    </form>

    <div class="surface-card overflow-hidden">
        @if ($articles->isEmpty())
            <x-empty-state title="Artikel tidak ditemukan" description="Coba ubah kata kunci, atau mulai menulis artikel baru."
                           icon="article" :action="route('admin.articles.create')" action-label="Tulis Artikel" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Artikel</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Terbit</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if ($article->thumbnail)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->thumbnail) }}"
                                                 alt="" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-surface-container-low flex items-center justify-center text-outline shrink-0">
                                                <span class="material-symbols-outlined text-[24px]">image</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.articles.edit', $article) }}"
                                               class="font-body-sm text-body-sm text-on-surface font-semibold hover:text-primary transition-colors line-clamp-2">
                                                {{ $article->title }}
                                            </a>
                                            <p class="font-mono font-label-md text-label-md text-outline">{{ $article->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $article->category?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $article->author?->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">
                                        {{ $article->published_at?->format('d/m/Y') ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $article->status === 'published',
                                        'bg-secondary-fixed text-on-secondary-fixed-variant' => $article->status === 'draft',
                                    ])>{{ $article->status === 'published' ? 'Terbit' : 'Draft' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                              onsubmit="return confirm('Hapus artikel {{ $article->title }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($articles->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$articles" />
                </div>
            @endif
        @endif
    </div>
@endsection