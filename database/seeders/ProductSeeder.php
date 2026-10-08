<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Gambar lokal hasil unduhan template asli (public/images).
     */
    private const IMAGES = [
        'royal-canin-indoor-adult',
        'whiskas-tuna-box',
        'pedigree-adult-beef-veggie',
        'pasir-tofu-soya-bean',
        'mainan-gigit-interaktif-tpr',
        'cat-scratching-post-sisal',
        'medicated-flea-fungal-shampoo',
        'wild-salmon-oil-softgels',
    ];

    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $brands = Brand::pluck('id', 'slug');

        /*
         | 8 produk pertama memakai data PERSIS seperti template asli
         | (nama, harga, diskon, rating) supaya homepage tetap sama tampilannya.
         */
        $catalog = [
            [
                'name' => 'Royal Canin Indoor Adult 2kg',
                'category' => 'kucing',
                'brand' => 'royal-canin',
                'price' => 390_000,
                'discount_price' => 312_000,
                'stock' => 48,
                'weight' => 2000,
                'rating' => 4.9,
                'reviews' => 342,
                'description' => 'Makanan kering premium untuk kucing dewasa yang sedentary, membantu mengurangi bau kotoran dan menjaga berat badan ideal.',
                'image' => 'royal-canin-indoor-adult',
            ],
            [
                'name' => 'Whiskas Pouch Tuna 85g Box (12 Pcs)',
                'category' => 'makanan-basah',
                'brand' => 'whiskas',
                'price' => 115_000,
                'discount_price' => 98_000,
                'stock' => 120,
                'weight' => 1020,
                'rating' => 4.8,
                'reviews' => 512,
                'description' => 'Makanan basah tuna segar asli kaya omega 3 dan 6 untuk bulu berkilau.',
                'image' => 'whiskas-tuna-box',
            ],
            [
                'name' => 'Pedigree Adult Beef & Veggie 3kg',
                'category' => 'anjing',
                'brand' => 'pedigree',
                'price' => 165_000,
                'discount_price' => null,
                'stock' => 76,
                'weight' => 3000,
                'rating' => 4.7,
                'reviews' => 228,
                'description' => 'Makanan kering anjing dewasa dengan kandungan daging tinggi agar rasa tetap menggugah selera.',
                'image' => 'pedigree-adult-beef-veggie',
            ],
            [
                'name' => 'Pasir Kucing Tofu Soya Bean 7L Green Tea',
                'category' => 'kucing',
                'brand' => 'k9-natural',
                'price' => 145_000,
                'discount_price' => 129_000,
                'stock' => 64,
                'weight' => 4000,
                'rating' => 4.6,
                'reviews' => 189,
                'description' => 'Pasir gumpal berbasis soybean dengan aroma green tea, cepat menyerap dan mudah dibuang.',
                'image' => 'pasir-tofu-soya-bean',
            ],
            [
                'name' => 'Mainan Gigit Interaktif TPR Chewer',
                'category' => 'mainan-aksesoris',
                'brand' => 'hartz',
                'price' => 89_000,
                'discount_price' => 72_000,
                'stock' => 150,
                'weight' => 350,
                'rating' => 4.5,
                'reviews' => 96,
                'description' => 'Mainan gigit dari bahan TPR yang tahan kunyah dan membantu membersihkan gigi.',
                'image' => 'mainan-gigit-interaktif-tpr',
            ],
            [
                'name' => 'Cat Scratching Post Sisal 60cm',
                'category' => 'mainan-aksesoris',
                'brand' => 'hartz',
                'price' => 235_000,
                'discount_price' => null,
                'stock' => 32,
                'weight' => 2500,
                'rating' => 4.7,
                'reviews' => 143,
                'description' => 'Pohon cakar dari bahan sisal alami dengan platform agar kucing lebih nyaman bermain.',
                'image' => 'cat-scratching-post-sisal',
            ],
            [
                'name' => 'Medicated Flea & Fungal Shampoo 250ml',
                'category' => 'perawatan-bulu',
                'brand' => 'vaseline-healthcare',
                'price' => 78_000,
                'discount_price' => 62_000,
                'stock' => 88,
                'weight' => 300,
                'rating' => 4.6,
                'reviews' => 87,
                'description' => 'Shampoo obat kutu dan jamur yang aman untuk kucing dan anjing sensitif.',
                'image' => 'medicated-flea-fungal-shampoo',
            ],
            [
                'name' => 'Wild Salmon Oil Softgels (100 Butir)',
                'category' => 'vitamin-suplemen',
                'brand' => 'k9-natural',
                'price' => 210_000,
                'discount_price' => 168_000,
                'stock' => 41,
                'weight' => 400,
                'rating' => 4.8,
                'reviews' => 175,
                'description' => 'Minyak salmon liar tinggi Omega 3 untuk meningkatkan kilau dan kesehatan bulu.',
                'image' => 'wild-salmon-oil-softgels',
            ],
        ];

        foreach ($catalog as $i => $data) {
            $product = Product::updateOrCreate(
                ['sku' => 'PCH'.str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT)],
                [
                    'category_id' => $categories[$data['category']] ?? null,
                    'brand_id' => $brands[$data['brand']] ?? null,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'description' => $data['description'],
                    'specification' => "Berat: {$data['weight']} gram\nGaransi: 100% produk original",
                    'price' => $data['price'],
                    'discount_price' => $data['discount_price'],
                    'stock' => $data['stock'],
                    'weight' => $data['weight'],
                    'status' => 'active',
                    'featured' => true,
                    'rating_average' => $data['rating'],
                    'rating_count' => $data['reviews'],
                    'sold_count' => random_int(5, 300),
                ],
            );

            $this->attachImage($product, $data['image']);
        }

        // 22 produk tambahan memakai factory
        $imageByCategory = [
            'anjing' => 'pedigree-adult-beef-veggie',
            'kucing' => 'royal-canin-indoor-adult',
            'burung' => 'wild-salmon-oil-softgels',
            'ikan-hias' => 'wild-salmon-oil-softgels',
            'kelinci' => 'pedigree-adult-beef-veggie',
            'hewan-kecil' => 'mainan-gigit-interaktif-tpr',
            'makanan-basah' => 'whiskas-tuna-box',
            'vitamin-suplemen' => 'wild-salmon-oil-softgels',
            'perawatan-bulu' => 'medicated-flea-fungal-shampoo',
            'mainan-aksesoris' => 'cat-scratching-post-sisal',
        ];

        Product::factory()
            ->count(22)
            ->create([
                'category_id' => fn () => Category::inRandomOrder()->value('id'),
                'brand_id' => fn () => Brand::inRandomOrder()->value('id'),
            ])
            ->each(function (Product $product) use ($imageByCategory) {
                $this->attachImage(
                    $product,
                    $imageByCategory[$product->category?->slug] ?? 'whiskas-tuna-box',
                );
            });
    }

    private function attachImage(Product $product, string $imageKey): void
    {
        if ($product->images()->exists()) {
            return;
        }

        $source = public_path("images/{$imageKey}.jpg");
        $relative = "products/{$imageKey}.jpg";

        if (! is_file($source)) {
            return;
        }

        Storage::disk('public')->put($relative, file_get_contents($source));

        $product->images()->create([
            'path' => $relative,
            'alt' => $product->name,
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }
}