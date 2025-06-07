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
        Schema::create('guide_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_page_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->json('content');
            $table->integer('order');
            $table->timestamps();

            // Índice composto para garantir ordem única por página
            $table->unique(['guide_page_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_components');
    }
};
