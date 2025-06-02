<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')
                ->comment('Reference to the product category')
                ->constrained('product_categories', 'id')
                ->onDelete('cascade')
                ->name('fk_products_category');
            $table->index('product_category_id', 'idx_products_category');
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('price');
            $table->string('stock');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
