@extends('layouts.app')

@section('title', 'Artikel & Tips')

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <section class="w-full py-10 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-breadcrumb :items="['Artikel' => null]" />

            <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Edukasi &amp; Insights</span>
            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-bold tracking-tight mt-1 mb-4">
                Artikel &amp; Tips Perawatan
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                Panduan praktis dari dokter hewan dan groomer berpengalaman untuk merawat Sahabat di rumah.
            </p>

            <form method="GET" class="mt-6 flex gap-2 max-w-lg">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                       placeholder="Cari artikel..." class="form-input">
                <button type="submit" class="px-6 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors shrink-0">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <section class="w-full py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <aside class="lg:col-span-1">
                    <div class="surface-card p-5 lg:sticky lg:top-24">
                        <h2 class="font-title-md text-title-md text-on-surface font-bold mb-4">Kategori</h2>
                        <nav class="space-y-1.5">
                            <a href="{{ route('artikel.index') }}"
                               @class([
                                   'flex items-center justify-between px-3 py-2 rounded-xl font-body-sm text-body-sm transition-colors',
                                   'bg-primary-container text-on-primary-container font-semibold' => empty($filters['kategori']),
                                   'text-on-surface-variant hover:bg-surface-container-low' => ! empty($filters['kategori']),
                               ])>
                                <span>Semua Artikel</span>
                            </a>

                            @foreach ($categories as $category)
                                <a href="{{ route('artikel.index', ['kategori' => $category->slug]) }}"
                                   @class([
                                       'flex items-center justify-between px-3 py-2 rounded-xl font-body-sm text-body-sm transition-colors',
                                       'bg-primary-container text-on-primary-container font-semibold' => ($filters['kategori'] ?? '') === $category->slug,
                                       'text-on-surface-variant hover:bg-surface-container-low' => ($filters['kategori'] ?? '') !== $category->slug,
                                   ])>
                                    <span>{{ $category->name }}</span>
                                    <span class="text-outline">({{ $category->articles_count }})</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <div class="lg:col-span-3">
                    @if ($articles->isEmpty())
                        <x-empty-state
                            title="Artikel tidak ditemukan"
                            description="Coba kata kunci lain atau pilih kategori berbeda."
                            icon="article"
                            :action="route('artikel.index')"
                            action-label="Lihat Semua Artikel" />
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($articles as $article)
                                <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                                    <a href="{{ route('artikel.show', $article->slug) }}" class="block aspect-[16/10] overflow-hidden bg-surface-container-low">
                                        <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                    </a>

                                    <div class="p-6 flex flex-col flex-1">
                                        @if ($article->category)
                                            <span class="font-label-caps text-label-caps text-primary uppercase font-bold tracking-wider mb-2">
                                                {{ $article->category->name }}
                                            </span>
                                        @endif

                                        <h2 class="font-title-md text-title-md text-on-surface font-bold leading-snug mb-2 line-clamp-2">
                                            <a href="{{ route('artikel.show', $article->slug) }}" class="hover:text-primary transition-colors">
                                                {{ $article->title }}
                                            </a>
                                        </h2>

                                        <p class="font-body-sm text-body-sm text-outline leading-relaxed line-clamp-3 mb-4">
                                            {{ $article->excerpt }}
                                        </p>

                                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-surface-container-low text-outline">
                                            <span class="font-label-md text-label-md flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                                {{ $article->published_at?->format('d M Y') }}
                                            </span>
                                            <span class="font-label-md text-label-md flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                                {{ number_format($article->views, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <x-pagination :paginator="$articles" />
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection