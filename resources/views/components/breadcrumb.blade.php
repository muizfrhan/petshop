@props(['items' => [], 'current' => null])

@if (! empty($items))
    <nav aria-label="Breadcrumb" class="mb-6">
        <ol class="flex flex-wrap items-center gap-1.5 font-body-sm text-body-sm text-outline">
            <li>
                <a class="hover:text-primary transition-colors" href="{{ route('beranda') }}">Beranda</a>
            </li>

            @foreach ($items as $label => $url)
                <li class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px] opacity-60">chevron_right</span>
                    @if ($url && ! $loop->last)
                        <a class="hover:text-primary transition-colors" href="{{ $url }}">{{ $label }}</a>
                    @else
                        <span @class([
                            'transition-colors',
                            'text-on-surface font-semibold' => $loop->last,
                        ]) aria-current="{{ $loop->last ? 'page' : 'false' }}">{{ $label }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif