@props(['paginator', 'elements' => null])

@php
    if ($elements === null) {
        $urlWindow = \Illuminate\Pagination\UrlWindow::make($paginator);

        $elements = array_filter([
            $urlWindow['first'],
            is_array($urlWindow['slider']) ? '...' : null,
            $urlWindow['slider'],
            is_array($urlWindow['last']) ? '...' : null,
            $urlWindow['last'],
        ]);
    }
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="mt-10 flex items-center justify-between gap-4 flex-wrap">
        <p class="font-body-sm text-body-sm text-outline">
            Menampilkan <span class="font-bold text-on-surface">{{ $paginator->firstItem() }}</span>
            sampai <span class="font-bold text-on-surface">{{ $paginator->lastItem() }}</span>
            dari <span class="font-bold text-on-surface">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 rounded-xl bg-surface-container-low text-outline font-label-md text-label-md cursor-not-allowed" aria-disabled="true">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="px-3 py-2 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface hover:bg-surface-container-low transition-colors font-label-md text-label-md font-semibold"
                   aria-label="Halaman sebelumnya">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-outline font-label-md text-label-md">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="px-3.5 py-2 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3.5 py-2 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface hover:bg-surface-container-low transition-colors font-label-md text-label-md font-semibold">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="px-3 py-2 rounded-xl bg-surface-container-lowest border border-surface-outline-app text-on-surface hover:bg-surface-container-low transition-colors font-label-md text-label-md font-semibold"
                   aria-label="Halaman berikutnya">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span class="px-3 py-2 rounded-xl bg-surface-container-low text-outline font-label-md text-label-md cursor-not-allowed" aria-disabled="true">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif