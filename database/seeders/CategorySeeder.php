<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Kategori inti yang sudah ada di template (section "KATEGORI HEWAN
     * KESAYANGAN") + kategori produk tambahan.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Anjing', 'icon' => 'pets', 'description' => 'Makanan, suplemen, dan perlengkapan untuk anjing peliharaan Anda.'],
            ['name' => 'Kucing', 'icon' => 'pets', 'description' => 'Nutrisi premium dan kebutuhan harian untuk kucing kesayangan.'],
            ['name' => 'Burung', 'icon' => 'flutter_dash', 'description' => 'Pakan burung kicau dan aksesoris sangkar untuk burung kesayangan.'],
            ['name' => 'Ikan Hias', 'icon' => 'water', 'description' => 'Pakan dan perawatan akuarium serta ikan hias.'],
            ['name' => 'Kelinci', 'icon' => 'cruelty_free', 'description' => 'Kebutuhan, pakan, dan kandang untuk kelinci peliharaan.'],
            ['name' => 'Hewan Kecil', 'icon' => 'pest_control', 'description' => 'Makanan dan perlengkapan untuk hamster, kelinci, dan hewan kecil lainnya.'],
            ['name' => 'Makanan Basah', 'icon' => 'restaurant', 'description' => 'Kaleng dan pouch makanan basah pilihan.'],
            ['name' => 'Vitamin & Suplemen', 'icon' => 'vaccines', 'description' => 'Vitamin, suplemen bulu, dan multivitamin hewan.'],
            ['name' => 'Perawatan Bulu', 'icon' => 'content_cut', 'description' => 'Shampoo, alat grooming, dan produk anti-kutu.'],
            ['name' => 'Mainan & Aksesoris', 'icon' => 'toys', 'description' => 'Mainan interaktif, sangkar, dan aksesoris untuk hewan peliharaan.'],
        ];

        foreach ($categories as $index => $data) {
            Category::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'status' => 'active',
                    'sort_order' => $index,
                ],
            );
        }
    }
}