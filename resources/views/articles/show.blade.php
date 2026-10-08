@extends('layouts.app')

@section('title', $article->title)

@section('content')
<main id="konten-utama" class="w-full pt-20 bg-surface flex-1">
    <article class="w-full py-10">
        <div class="max-w-4xl mx-auto px-6">
            <x-breadcrumb :items="[
                'Artikel' => route('artikel.index'),
                $article->title => null,
            ]" />

            @if ($article->category)
                <a href="{{ route('artikel.index', ['kategori' => $article->category->slug]) }}"
                   class="font-label-caps text-label-caps text-primary uppercase font-bold tracking-wider hover:underline">
                    {{ $article->category->name }}
                </a>
            @endif

            <h1 class="font-headline-xl text-headline-xl-mobile lg:text-headline-xl text-on-surface font-extrabold tracking-tight mt-2 mb-4">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-outline pb-6 mb-6 border-b border-surface-container-low">
                @if ($article->author)
                    <span class="flex items-center gap-2 font-body-sm text-body-sm">
                        <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-primary text-[16px]">person</span>
                        </span>
                        {{ $article->author->name }}
                    </span>
                @endif
                <span class="flex items-center gap-1.5 font-body-sm text-body-sm">
                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                    {{ $article->published_at?->format('d F Y') }}
                </span>
                <span class="flex items-center gap-1.5 font-body-sm text-body-sm">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    {{ number_format($article->views, 0, ',', '.') }} pembaca
                </span>
            </div>

            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->title }}"
                 class="w-full aspect-[16/9] object-cover rounded-3xl bg-surface-container-low mb-8">

            @if ($article->excerpt)
                <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed mb-8 pb-8 border-b border-surface-container-low">
                    {{ $article->excerpt }}
                </p>
            @endif

{{-- Konten berasal dari admin. Dinormalisasi server-side (Article::sanitizeContent)
                             agar hanya tag markup yang aman yang lolos ke halaman. --}}
            <div class="article-content text-on-surface-variant leading-relaxed mb-14">
                {!! $article->sanitizedContent() !!}
            </div>

            @if ($related->isNotEmpty())
                <div class="pt-8 border-t border-surface-container-low">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-6">Bacaan Terkait</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @foreach ($related as $item)
                            <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm group flex flex-col">
                                <a href="{{ route('artikel.show', $item->slug) }}" class="block aspect-[16/10] overflow-hidden bg-surface-container-low">
                                    <img src="{{ $item->thumbnailUrl() }}" alt="{{ $item->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                </a>
                                <div class="p-4 flex flex-col flex-1">
                                    <h3 class="font-title-md text-title-md text-on-surface font-bold line-clamp-2 mb-2">
                                        <a href="{{ route('artikel.show', $item->slug) }}" class="hover:text-primary transition-colors">
                                            {{ $item->title }}
                                        </a>
                                    </h3>
                                    <p class="font-label-md text-label-md text-outline mt-auto">
                                        {{ $item->published_at?->format('d M Y') }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>
</main>

@push('head')
<style>
    .article-content h2 { font-size: 24px; font-weight: 700; color: var(--color-on-surface); margin: 2rem 0 1rem; }
    .article-content h3 { font-size: 18px; font-weight: 600; color: var(--color-on-surface); margin: 1.75rem 0 0.75rem; }
    .article-content p  { margin-bottom: 1.25rem; }
    .article-content ul,
    .article-content ol { margin: 0 0 1.5rem; padding-left: 1.5rem; }
    .article-content li { margin-bottom: 0.5rem; list-style: disc; }
    .article-content ol > li { list-style: decimal; }
    .article-content a  { color: var(--color-primary); text-decoration: underline; }
    .article-content strong { color: var(--color-on-surface); font-weight: 700; }
</style>
@endpush
@endsection