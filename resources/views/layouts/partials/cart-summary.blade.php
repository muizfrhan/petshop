@if (! $totals || ($totals['subtotal'] ?? 0) <= 0)
    <div class="space-y-1.5 opacity-50">
        <div class="flex justify-between font-body-sm text-body-sm text-outline">
            <span>Subtotal Belanja</span><span>Rp 0</span>
        </div>
        <div class="flex justify-between font-body-sm text-body-sm text-outline">
            <span>Perkiraan Ongkir</span>
            <span class="text-surface-tint font-semibold">Dihitung saat Checkout</span>
        </div>
        <div class="flex justify-between font-headline-sm text-headline-sm text-on-surface font-bold pt-2">
            <span>Total Estimasi</span><span class="text-primary">Rp 0</span>
        </div>
    </div>
@else
    <div class="space-y-1.5">
        <div class="flex justify-between font-body-sm text-body-sm text-outline">
            <span>Subtotal Belanja ({{ $totals['count'] ?? 0 }} item)</span>
            <span>Rp {{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
        </div>

        @if ($totals['discount'] > 0)
            <div class="flex justify-between font-body-sm text-body-sm text-primary font-semibold">
                <span>Diskon</span>
                <span>- Rp {{ number_format($totals['discount'], 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="flex justify-between font-body-sm text-body-sm text-outline">
            <span>Perkiraan Ongkir</span>
            <span class="text-surface-tint font-semibold">Dihitung saat Checkout</span>
        </div>

        <div class="flex justify-between font-headline-sm text-headline-sm text-on-surface font-bold pt-2 border-t border-surface-container-lowest mt-2">
            <span>Total Estimasi</span>
            <span class="text-primary">Rp {{ number_format($totals['subtotal'] - $totals['discount'], 0, ',', '.') }}</span>
        </div>
    </div>
@endif