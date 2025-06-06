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
        Schema::create('product_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->onDelete('cascade');
            $table->string('language', 2); // ISO 639-1 language codes
            $table->string('name');
            $table->timestamps();
            $table->unique(['product_category_id', 'language'], 'idx_cat_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category_translations');
    }
};
