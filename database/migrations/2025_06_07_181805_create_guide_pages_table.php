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
        Schema::create('guide_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained()->onDelete('cascade');
            $table->string('language');
            $table->integer('order');
            $table->timestamps();

            // Índice composto para garantir ordem única por guia e idioma
            $table->unique(['guide_id', 'language', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_pages');
    }
};
