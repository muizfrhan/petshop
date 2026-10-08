<section class="w-full py-16 bg-surface-container-low">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-12">
            <span class="font-label-caps text-label-caps text-secondary font-bold tracking-wider uppercase">Kata Mereka</span>
            <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mt-1">Cerita Pet Parents</h2>
        </div>

        @if ($testimonials->isEmpty())
            <x-empty-state
                title="Belum ada ulasan"
                description="Ulasan akan tampil setelah ada pelanggan yang menulis ulasan."
                icon="reviews" />
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($testimonials as $review)
                    <figure class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                @if ($review->user?->avatar)
                                    <img src="{{ $review->user->avatarUrl() }}" alt="{{ $review->user->name }}"
                                         class="w-11 h-11 rounded-full object-cover">
                                @else
                                    <div class="w-11 h-11 rounded-full bg-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-primary text-[20px]">person</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-title-md text-title-md text-on-surface font-bold leading-tight">{{ $review->user?->name }}</p>
                                    <p class="font-label-caps text-label-caps text-outline">{{ $review->product?->name }}</p>
                                </div>
                            </div>
                            <span aria-hidden="true" class="material-symbols-outlined text-[28px] text-surface-container-highest">format_quote</span>
                        </div>

                        <x-rating :rating="$review->rating" :show-count="false" class="mb-3" />

                        <blockquote class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed flex-1">
                            "{{ \Illuminate\Support\Str::limit($review->comment, 180) }}"
                        </blockquote>

                        <figcaption class="mt-5 pt-4 border-t border-surface-container-low flex items-center gap-2 text-primary">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            <span class="font-label-md text-label-md font-semibold">Pembelian Terverifikasi</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>