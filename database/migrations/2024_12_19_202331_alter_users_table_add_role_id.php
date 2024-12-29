<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('password');
            $table->foreign('role_id')->references('id')->on('roles')->nullable();
        });
        
        User::create([
            'email' => 'admin@email.com',
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        User::create([
            'email' => 'client@email.com',
            'name' => 'Client',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
