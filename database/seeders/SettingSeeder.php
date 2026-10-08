<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'store_name' => 'PETCARE HOUSE',
            'store_tagline' => 'Semua Kebutuhan Hewan Kesayangan, Dalam Satu Tempat',
            'whatsapp_number' => '6281234567890',
            'support_email' => 'cs@petcarehouse.test',
            'low_stock_threshold' => '10',
            'free_shipping_min' => '150000',
            'bank_bca' => '1234567890',
            'bank_mandiri' => '9876543210',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general'],
            );
        }
    }
}