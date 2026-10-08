@props([
    'name' => 'modal',
    'title' => null,
    'icon' => null,
    'maxWidth' => 'max-w-lg',
])

<div class="hidden fixed inset-0 z-[80] overflow-y-auto bg-inverse-surface/40 backdrop-blur-sm"
     id="{{ $name }}-modal"
     data-modal="{{ $name }}"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $name }}-modal-title">
    <div class="flex items-center justify-center min-h-full p-4">
        <div class="bg-surface-container-lowest rounded-3xl {{ $maxWidth }} w-full p-6 sm:p-8 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200"
             data-modal-panel>

            <button type="button"
                    class="absolute top-4 right-4 p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low transition-colors"
                    data-modal-close="{{ $name }}"
                    aria-label="Tutup">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>

            @if ($icon)
                <div class="w-14 h-14 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mb-4">
                    <span class="material-symbols-outlined text-[28px]">{{ $icon }}</span>
                </div>
            @endif

            @if ($title)
                <h2 id="{{ $name }}-modal-title" class="font-headline-sm text-headline-sm text-on-surface font-bold pr-10">{{ $title }}</h2>
            @endif

            <div class="{{ $title ? 'mt-3' : 'mt-6' }}">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>