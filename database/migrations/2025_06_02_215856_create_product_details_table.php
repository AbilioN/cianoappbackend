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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'image',
                'large_image',
                'medium_image',
                'small_image',
                'text',
                'medium_text',
                'small_text',
                'divider',
                'list',
                'ordered_list',
                'title',
                'title_left',
                'description',
                'youtube',
                'notification_button',
                'yes_or_no',
                'link_button',
            ]);
            $table->json('content');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
