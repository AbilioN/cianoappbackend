<?php

namespace Database\Seeders;

use App\Models\Aquarium;
use App\Models\Notification;
use App\Models\NotificationBody;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        Aquarium::create([
            'user_id' => 1,
            'name' => 'Test Aquarium',
            'slug' => 'test-aquarium',
        ]);

        Notification::create([
            'name' => 'Fish Aquarium',
            'slug' => 'fish-aquarium',
            // 'key' => 'fish_aquarium',
            'duration_type' => 'seconds',
            'duration_value' => 10,
            'type' => 'single',
            // 'start_date' => now(),
            // 'end_date' => now()->addSeconds(10),
            // 'callback_key' => 'fish_aquarium',
        ]);

        $notifications = [
            'en' => [
                'title' => 'Test Notification',
                'body' => 'This is a test notification',
            ],
            'pt' => [
                'title' => 'Notificação de Teste',
                'body' => 'Esta é uma notificação de teste vinda diretamente do back end',
            ],
            'it' => [
                'title' => 'Notifica di Test',
                'body' => 'Questa è una notifica di test',
            ],
            'es' => [
                'title' => 'Notificación de Prueba',
                'body' => 'Esta es una notificación de prueba',
            ],
            'de' => [
                'title' => 'Testbenachrichtigung',
                'body' => 'Dies ist eine Testbenachrichtigung',
            ],
            'fr' => [
                'title' => 'Notification de Test',
                'body' => 'Ceci est une notification de test',
            ],
        ];

        foreach ($notifications as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 1,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
    }
}
