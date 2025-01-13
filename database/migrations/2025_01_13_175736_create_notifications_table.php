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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            // $table->string('key')->nullable();
            $table->string('duration_type');
            $table->integer('duration_value');
            $table->string('type');
            $table->boolean('is_read')->default(false);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('renew_date')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->string('callback_key')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            // notifications->body(lang);

            // lang (en, pt, it, fr, es, de)
            // title
            // body

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
