<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Royal Canin', 'description' => 'Nutrisi veteriner premium dari Prancis untuk anjing dan kucing.'],
            ['name' => 'Whiskas', 'description' => 'Makanan basah dan kering favorit kucing dari Mars.'],
            ['name' => 'Pedigree', 'description' => 'Makanan anjing terjangkau dengan rasa daging yang menggugah selera.'],
            ['name' => 'Me-O', 'description' => 'Makanan kucing dan anjing lokal dengan harga bersahabat.'],
            ['name' => 'Bolt', 'description' => 'Makanan kering dan cemilan untuk anjing Indonesia.'],
            ['name' => 'Purina Pro Plan', 'description' => 'Nutrisi ilmiah dari Purina untuk anjing dan kucing.'],
            ['name' => 'Hill\'s Science Diet', 'description' => 'Diet khusus dari dokter hewan untuk kebutuhan kesehatan hewan.'],
            ['name' => 'Hartz', 'description' => 'Aksesoris dan perawatan hewan peliharaan.'],
            ['name' => 'K9 Natural', 'description' => 'Makanan anjing premium bebas pengawet buatan.'],
            ['name' => 'Vaseline Healthcare', 'description' => 'Perawatan kulit dan bulu dengan formula lembut.'],
        ];

        foreach ($brands as $data) {
            Brand::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => 'active',
                ],
            );
        }
    }
}