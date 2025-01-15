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
        Schema::create('aquarium_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aquarium_id')->constrained('aquaria');
            $table->foreignId('notification_id')->constrained('notifications');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('renew_date')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_active')->default(true);
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aquarium_notifications');
    }
};
