<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', User::ROLE_CUSTOMER)->where('status', 'active')->get();
        $products = Product::where('status', 'active')->where('stock', '>', 5)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command?->warn('TransactionSeeder dilewati: tidak ada customer atau produk aktif.');

            return;
        }

        $statuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PENDING,
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_COMPLETED,
            Order::STATUS_COMPLETED,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        $shippingMethods = ['reguler', 'express', 'same_day'];
        $paymentMethods = ['bank_transfer', 'qris', 'ewallet', 'cod'];

        $reviewable = [];

        foreach (range(1, 28) as $i) {
            $customer = $customers->random();
            $status = $statuses[random_int(0, count($statuses) - 1)];
            $createdAt = now()->subDays(random_int(0, 45))->subHours(random_int(0, 23));

            $chosen = $products->random(min(3, $products->count()));
            $items = [];
            $subtotal = 0;

            foreach ($chosen as $product) {
                $qty = random_int(1, 3);
                $price = $product->final_price;
                $subtotal += $price * $qty;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $product->primaryImage->first()?->path,
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $price * $qty,
                ];
            }

            $shippingMethod = $shippingMethods[random_int(0, 2)];
            $shippingCost = config("petshop.shipping.{$shippingMethod}.cost");

            $order = Order::create([
                'order_number' => 'PCH-'.$createdAt->format('Ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => $customer->id,
                'status' => $status,
                'customer_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone ?: '08'.fake()->numerify('##########'),
                'whatsapp' => $customer->phone ?: '08'.fake()->numerify('##########'),
                'street' => 'Jl. Melati No. '.random_int(1, 200),
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'Coblong',
                'postal_code' => '40132',
                'shipping_method' => $shippingMethod,
                'shipping_address' => 'Jl. Melati, Coblong, Bandung, Jawa Barat, 40132',
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping_cost' => $shippingCost,
                'tax' => 0,
                'total' => $subtotal + $shippingCost,
                'payment_method' => $paymentMethods[random_int(0, 3)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($items as $item) {
                OrderItem::create($item + ['order_id' => $order->id]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $order->payment_method,
                'status' => $status === Order::STATUS_PENDING ? 'unpaid' : 'paid',
                'reference' => strtoupper(Str::substr($order->payment_method, 0, 3)).'-'.$createdAt->format('YmdHis'),
                'amount' => $order->total,
                'paid_at' => $status === Order::STATUS_PENDING ? null : $createdAt->copy()->addHour(),
                'payload' => ['driver' => 'manual', 'seed' => true],
            ]);

            if ($status === Order::STATUS_PENDING) {
                continue;
            }

            $order->update([
                'paid_at' => $createdAt->copy()->addHour(),
                'completed_at' => $status === Order::STATUS_COMPLETED
                    ? $createdAt->copy()->addDays(3)
                    : null,
            ]);

            if ($status === Order::STATUS_COMPLETED) {
                $reviewable[] = ['order' => $order, 'items' => $items, 'customer' => $customer];
            }
        }

        // Ulasan hanya untuk pesanan completed (sesuai aturan bisnis aplikasi)
        $comments = [
            'Produk original dan dikemas dengan rapi. Pengiriman cepat, recommend!',
            'Kualitas sesuai harga. Sahabat saya suka rasanya sejak ganti makanan ini.',
            'Pelayanan ramah dan produk sampai dengan aman. Pasti belanja lagi di sini.',
            'Packing bubble wrap tebal, produk tidak ada yang rusak. Recommended banget!',
            'Barang sesuai deskripsi dan tidak ada cacat. Akan saya pesan lagi.',
            'Harga bersahabat, pengiriman sesuai estimasi. Puas dengan pelayanannya.',
        ];

        foreach ($reviewable as $entry) {
            foreach (array_slice($entry['items'], 0, random_int(1, 2)) as $item) {
                $exists = Review::where('user_id', $entry['customer']->id)
                    ->where('product_id', $item['product_id'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                Review::create([
                    'user_id' => $entry['customer']->id,
                    'product_id' => $item['product_id'],
                    'order_id' => $entry['order']->id,
                    'rating' => random_int(4, 5),
                    'comment' => $comments[array_rand($comments)],
                    'status' => Review::STATUS_PUBLISHED,
                ]);
            }
        }

        // Sinkronkan agregat rating produk dari ulasan yang published
        foreach ($products as $product) {
            $stats = Review::where('product_id', $product->id)
                ->published()
                ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total')
                ->first();

            if ((int) $stats->total > 0) {
                $product->update([
                    'rating_average' => round((float) $stats->avg_rating, 2),
                    'rating_count' => (int) $stats->total,
                ]);
            }
        }

        // Booking grooming / layanan
        $services = Service::active()->get();

        if ($services->isEmpty()) {
            $this->command?->warn('Booking dilewati: tidak ada layanan aktif.');

            return;
        }

        $petNames = ['Milo', 'Kitty', 'Bella', 'Max', 'Coco', 'Luna', 'Rocky', 'Mochi', 'Simba', 'Nala', 'Buddy', 'Kiwi'];

        foreach (range(1, 12) as $i) {
            $customer = $customers->random();
            $service = $services->random();

            ServiceBooking::create([
                'user_id' => $customer->id,
                'service_id' => $service->id,
                'customer_name' => $customer->name,
                'phone' => $customer->phone ?: '08'.fake()->numerify('##########'),
                'email' => $customer->email,
                'pet_name' => $petNames[array_rand($petNames)],
                'pet_type' => $service->name === 'Vaksinasi & Microchip' ? 'anjing' : ['anjing', 'kucing'][random_int(0, 1)],
                'pet_breed' => ['Mixed', 'Golden Retriever', 'Persia', 'Shih Tzu'][random_int(0, 3)],
                'pet_weight' => random_int(3, 25),
                'booking_date' => now()->addDays(random_int(-5, 10)),
                'booking_time' => ['09:00', '10:30', '13:00', '15:30', '17:00'][random_int(0, 4)],
                'notes' => 'Mohon datang sebelum pukul 10 pagi ya.',
                'status' => ServiceBooking::STATUS_PENDING,
                'price' => $service->price,
            ]);
        }

        $this->command?->info('TransactionSeeder selesai: pesanan, pembayaran, ulasan, dan booking dibuat.');
    }
}