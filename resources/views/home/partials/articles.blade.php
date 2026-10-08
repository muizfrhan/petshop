<section class="w-full py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Edukasi &amp; Insights</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1">Artikel &amp; Tips Perawatan</h2>
            </div>
            <a href="{{ route('artikel.index') }}"
               class="shrink-0 px-5 py-2.5 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface font-label-lg text-label-lg font-semibold hover:bg-surface-container-low transition-colors inline-flex items-center gap-2">
                Semua Artikel
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        @if ($articles->isEmpty())
            <x-empty-state
                title="Belum ada artikel"
                description="Artikel terbaru akan dipublikasikan di sini."
                icon="article" />
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($articles as $article)
                    <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                        <a href="{{ route('artikel.show', $article->slug) }}" class="block aspect-[16/10] overflow-hidden bg-surface-container-low">
                            <img src="{{ $article->thumbnailUrl() }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </a>

                        <div class="p-6 flex flex-col flex-1">
                            @if ($article->category)
                                <span class="font-label-caps text-label-caps text-primary uppercase font-bold tracking-wider mb-2">
                                    {{ $article->category->name }}
                                </span>
                            @endif

                            <h3 class="font-title-md text-title-md text-on-surface font-bold leading-snug mb-2 line-clamp-2">
                                <a href="{{ route('artikel.show', $article->slug) }}" class="hover:text-primary transition-colors">{{ $article->title }}</a>
                            </h3>

                            <p class="font-body-sm text-body-sm text-outline leading-relaxed line-clamp-3 mb-4">{{ $article->excerpt }}</p>

                            <div class="mt-auto flex items-center justify-between pt-4 border-t border-surface-container-low text-outline">
                                <span class="font-label-md text-label-md flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    {{ $article->published_at?->format('d M Y') }}
                                </span>
                                @if ($article->author)
                                    <span class="font-label-md text-label-md">{{ $article->author->name }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>