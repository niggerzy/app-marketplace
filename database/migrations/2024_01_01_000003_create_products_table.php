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
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('sku', 100)->unique();
            $table->decimal('price', 15, 2);
            $table->decimal('cost', 15, 2)->nullable();
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10);
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index(['slug', 'is_active']);
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
