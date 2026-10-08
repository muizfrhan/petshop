<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 40)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();

            $table->string('status', 30)->default('pending')->index();

            // snapshot data pengiriman (guest tetap punya order)
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('whatsapp', 30)->nullable();
            $table->string('street', 500);
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('district', 100)->nullable();
            $table->string('postal_code', 10);

            $table->string('shipping_method', 30)->default('reguler');
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('tax')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->string('coupon_code', 40)->nullable();

            $table->string('payment_method', 30)->default('bank_transfer');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            // nama & harga disimpan saat transaksi agar riwayat tidak berubah
            // walaupun produk nanti diedit/dihapus admin
            $table->string('product_name');
            $table->string('product_sku', 60)->nullable();
            $table->string('product_image')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->timestamps();

            $table->index(['order_id', 'product_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('method', 30);
            $table->string('status', 30)->default('unpaid')->index();
            $table->string('reference', 80)->nullable()->index();
            $table->unsignedBigInteger('amount')->default(0);
            $table->timestamp('paid_at')->nullable();
            // payload gateway (JSON) supaya mudah integrasi Midtrans/Xendit/Tripay
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};