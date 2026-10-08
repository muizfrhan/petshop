<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'PETHEMAT30',
                'name' => 'Diskon Awal Pendaftaran',
                'type' => 'percent',
                'value' => 30,
                'min_purchase' => 150_000,
                'quota' => 500,
                'status' => 'active',
            ],
            [
                'code' => 'GRATISONGKIR',
                'name' => 'Gratis Ongkir Minimum 100rb',
                'type' => 'fixed',
                'value' => 50_000,
                'min_purchase' => 100_000,
                'quota' => null,
                'status' => 'active',
            ],
            [
                'code' => 'VAKSIN10',
                'name' => 'Diskon Layanan Vaksinasi',
                'type' => 'fixed',
                'value' => 25_000,
                'min_purchase' => 100_000,
                'quota' => 200,
                'status' => 'active',
            ],
            [
                'code' => 'GROOMING15',
                'name' => 'Diskon Grooming 15%',
                'type' => 'percent',
                'value' => 15,
                'min_purchase' => 75_000,
                'quota' => null,
                'status' => 'active',
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'value' => $data['value'],
                    'min_purchase' => $data['min_purchase'],
                    'quota' => $data['quota'],
                    'used_count' => 0,
                    'status' => $data['status'],
                    'starts_at' => now()->subDay(),
                    'expires_at' => now()->addMonths(6),
                ],
            );
        }
    }
}