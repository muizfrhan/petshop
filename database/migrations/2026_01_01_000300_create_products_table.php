<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 60)->unique();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('discount_price')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('weight')->default(0);
            $table->string('status', 20)->default('active')->index();
            $table->boolean('featured')->default(false)->index();
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedBigInteger('sold_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'status']);
            $table->index(['status', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};