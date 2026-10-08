<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    private const PRODUCTS = [
        ['name' => 'Makanan Kucing Adult Salmon', 'desc' => 'Makanan kering kucing dewasa dengan rasa salmon yang tinggi omega 3 untuk bulu berkilau dan pencernaan yang sehat.'],
        ['name' => 'Makanan Kucing Kitten Chicken', 'desc' => 'Makanan khusus anak kucing dengan protein ayam tinggi untuk mendukung pertumbuhan dan sistem imun.'],
        ['name' => 'Makanan Anjing Adult Beef', 'desc' => 'Makanan kering anjing dewasa dengan kadar daging sapi tinggi agar otot tetap kuat dan berat badan ideal.'],
        ['name' => 'Makanan Anjing Puppy Small Breed', 'desc' => 'Formula khusus anjing ras kecil dengan ukuran kibble kecil yang mudah dikunyah anak anjing.'],
        ['name' => 'Makanan Basah Kucing Tuna Pouch', 'desc' => 'Pouch makanan basah rasa tuna segar dengan saus gurih yang menggugah selera kucing pemilih makanan.'],
        ['name' => 'Makanan Basah Anjing Chicken Gravy', 'desc' => 'Makanan basah anjing dengan kaldu ayam lembut, cocok dicampur nasi atau diberikan langsung.'],
        ['name' => 'Pasir Kucing Clumping Wangi Lavender', 'desc' => 'Pasir batuan bentonit yang menggumpal cepat, menyerap bau maksimal, dan tidak menempel di kaki.'],
        ['name' => 'Snack Training Anjing Soft Bites', 'desc' => 'Camilan lembut berukuran kecil untuk melatih anjing, rendah lemak dan mudah dicerna.'],
        ['name' => 'Snack Kucing Freeze Dried Chicken', 'desc' => 'Camilan ayam beku kering yang mempertahankan nutrisi alami, disukai kucing segala usia.'],
        ['name' => 'Multivitamin Hewan Cair 250ml', 'desc' => 'Suplemen cair lengkap dengan vitamin dan mineral untuk menjaga daya tahan tubuh hewan peliharaan.'],
        ['name' => 'Suplemen Bulu & Kulit Omega Plus', 'desc' => 'Kombinasi omega 3 dan 6 yang membantu mengurangi rontok dan menjaga kulit tetap sehat.'],
        ['name' => 'Shampoo Anti Kutu Herbal 250ml', 'desc' => 'Shampoo berbahan herbal yang membantu membunuh kutu dan jamur tanpa mengeringkan kulit.'],
        ['name' => 'Shampoo Wajah Hewan Gentle 200ml', 'desc' => 'Shampoo lembut bebas pewangi untuk membersihkan area wajah tanpa menyengat mata.'],
        ['name' => 'Mainan Gigit Karet Tahan Lama', 'desc' => 'Mainan gigit dari karet food grade yang membantu membersihkan gigi dan mengurangi kebosanan.'],
        ['name' => 'Mainan Bulu Pancing Kucing', 'desc' => 'Mainan pancing berbulu yang melatih refleks bermain kucing sekaligus menjaga kebugarannya.'],
        ['name' => 'Pohon Cakar Sisal 45cm', 'desc' => 'Tempat cakar berbahan serat sisal alami agar kucing tidak mencakar sofa dan perabot rumah.'],
        ['name' => 'Tempat Makan Ganda Anti Slip', 'desc' => 'Mangkuk makan ganda dengan alas anti slip, mudah dibersihkan dan tahan lama.'],
        ['name' => 'Botol Minum Otomatis 500ml', 'desc' => 'Botol minum gantung yang mengalirkan air bersih secara otomatis untuk kandang dan sangkar.'],
        ['name' => 'Kandang Portabel Lipat', 'desc' => 'Kandang lipat ringan dengan ventilasi baik, mudah dibawa untuk perjalanan dan penitipan.'],
        ['name' => 'Sisir Bulu Anti Kusut', 'desc' => 'Sisir bergagang lembut dengan ujung bulat yang tidak melukai kulit saat menyisir bulu panjang.'],
        ['name' => 'Tali Kekang Anti Lari Comfort', 'desc' => 'Set kekang dan tali dengan bantalan empuk agar anjing nyaman saat berjalan santai.'],
        ['name' => 'Pakan Burung Kicau Premium 1kg', 'desc' => 'Pakan burung kicau racikan lengkap dengan vitamin agar suara lebih merdu dan bulu indah.'],
        ['name' => 'Pelet Ikan Hias Color Enhancer', 'desc' => 'Pelet apung yang memperkuat warna ikan hias dan tidak mencemari air akuarium.'],
        ['name' => 'Vitamin Kucing Cair Taurin 100ml', 'desc' => 'Suplemen taurin untuk kesehatan jantung dan retina, diberikan satu kali sehari bersama makanan.'],
    ];

    private const SIZES = ['500 g', '1 kg', '2 kg', '3 kg', '5 kg', '250 ml', '500 ml', '60 cm', '2 pcs'];

    public function definition(): array
    {
        $product = fake()->randomElement(self::PRODUCTS);
        $name = $product['name'].' '.fake()->randomElement(self::SIZES);
        $price = fake()->numberBetween(25_000, 1_500_000);
        $onSale = fake()->boolean(45);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'sku' => 'PCH'.fake()->unique()->numerify('######'),
            'description' => $product['desc'].' '.fake()->randomElement([
                'Cocok untuk pemakaian sehari-hari di rumah.',
                'Tersedia dalam kemasan praktis yang mudah disimpan.',
                'Diproduksi dengan standar kualitas yang terjaga.',
                'Pilihan tepat untuk menjaga kesehatan Sahabat Anda.',
            ]),
            'specification' => 'Berat: '.fake()->numberBetween(100, 5000)." gram\nGaransi: 100% original",
            'price' => $price,
            'discount_price' => $onSale ? (int) ($price * 0.85) : null,
            'stock' => fake()->boolean(12) ? 0 : fake()->numberBetween(5, 200),
            'weight' => fake()->numberBetween(100, 5000),
            'status' => fake()->boolean(90) ? 'active' : 'inactive',
            'featured' => fake()->boolean(20),
            'rating_average' => round(fake()->randomFloat(1, 3.9, 5.0), 1),
            'rating_count' => fake()->numberBetween(3, 180),
            'sold_count' => fake()->numberBetween(3, 400),
        ];
    }

    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $price = $attributes['price'] ?? 100_000;

            return [
                'price' => $price,
                'discount_price' => (int) ($price * 0.8),
            ];
        });
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function lowStock(): static
    {
        return $this->state(fn () => ['stock' => fake()->numberBetween(1, 5)]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}
