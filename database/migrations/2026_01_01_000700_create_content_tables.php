<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('includes')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->string('image')->nullable();
            $table->string('icon', 60)->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->boolean('featured')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();

            $table->string('customer_name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('pet_name');
            $table->string('pet_type', 40)->nullable();
            $table->string('pet_breed', 100)->nullable();
            $table->decimal('pet_weight', 6, 2)->nullable();

            $table->date('booking_date');
            $table->string('booking_time', 10);
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->unsignedBigInteger('price')->default(0);
            $table->text('admin_note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['booking_date', 'status']);
        });

        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_category_id')->nullable()->constrained('article_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status', 20)->default('published')->index();
            $table->timestamps();
            $table->softDeletes();

            // satu produk hanya boleh direview satu kali per user
            $table->unique(['user_id', 'product_id']);
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('service_bookings');
        Schema::dropIfExists('services');
    }
};