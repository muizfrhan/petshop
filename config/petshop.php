<?php

return [
    'low_stock_threshold' => env('PETSHP_LOW_STOCK_THRESHOLD', 10),

    'store' => [
        'name' => env('PETSHP_STORE_NAME', 'PETCARE HOUSE'),
        'whatsapp' => env('PETSHP_WHATSAPP_NUMBER', '6281234567890'),
        'email' => env('PETSHP_SUPPORT_EMAIL', 'cs@petcarehouse.test'),
    ],

    /*
    | Metode pengiriman. Besaran ongkir selalu dihitung di backend
    | (CheckoutService) dan TIDAK PERNAH berasal dari input form.
    */
    'shipping' => [
        'reguler' => ['label' => 'Reguler', 'cost' => 15000, 'estimate' => '2-4 hari kerja'],
        'express' => ['label' => 'Express', 'cost' => 35000, 'estimate' => '1-2 hari kerja'],
        'same_day' => ['label' => 'Same Day', 'cost' => 60000, 'estimate' => 'hari yang sama'],
    ],

    'payment_methods' => [
        'bank_transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
        'ewallet' => 'E-Wallet',
        'cod' => 'COD',
    ],

    'order_statuses' => [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],

    'booking_statuses' => [
        'pending' => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'rescheduled' => 'Dijadwalkan Ulang',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],
];