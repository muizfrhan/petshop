@php
    $toasts = array_merge(
        session('success') ? [['message' => session('success'), 'icon' => 'check_circle', 'variant' => 'success']] : [],
        session('error') ? [['message' => session('error'), 'icon' => 'error', 'variant' => 'error']] : [],
        session('warning') ? [['message' => session('warning'), 'icon' => 'warning', 'variant' => 'warning']] : [],
    );
@endphp

{{-- Wadah toast. Pesan session dirender server-side supaya tetap tampil
     walau JavaScript belum termuat / gagal. --}}
<div class="fixed top-24 right-6 z-[70] flex flex-col gap-2 pointer-events-none" id="toast-container">
    @foreach ($toasts as $toast)
        @php
            $palette = match ($toast['variant']) {
                'error' => 'bg-error text-on-error',
                'warning' => 'bg-secondary text-on-primary',
                default => 'bg-primary text-on-primary',
            };
        @endphp
        <div class="flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl pointer-events-none {{ $palette }}"
             data-toast>
            <span class="material-symbols-outlined text-[20px]">{{ $toast['icon'] }}</span>
            <span class="font-label-md text-label-md font-bold">{{ $toast['message'] }}</span>
        </div>
    @endforeach
</div>

{{-- Target untuk toast dari AJAX --}}
<template id="toast-template">
    <div class="flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl pointer-events-none transition-all duration-300 translate-y-2 opacity-0"
         data-toast>
        <span class="material-symbols-outlined text-[20px]" data-toast-icon>check_circle</span>
        <span class="font-label-md text-label-md font-bold" data-toast-message></span>
    </div>
</template>