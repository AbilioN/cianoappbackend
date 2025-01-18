<?php

namespace Database\Seeders;

use App\Models\Aquarium;
use App\Models\AquariumNotification;
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
            'user_id' => 2,
            'name' => 'Test Aquarium',
            'slug' => 'test-aquarium',
        ]);

        Aquarium::create([
            'user_id' => 2,
            'name' => 'Test Aquaria',
            'slug' => 'test-aquaria',
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
        Notification::create([
            'name' => 'Snake Aquarium',
            'slug' => 'snake-aquarium',
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

        AquariumNotification::create([
            'aquarium_id' => 1,
            'notification_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'renew_date' => now()->addDays(6),
            'is_read' => false,
            'is_active' => true,
            'read_at' => null,
        ]);
        AquariumNotification::create([
            'aquarium_id' => 1,
            'notification_id' => 2,
            'start_date' => now()->subDays(3),
            'end_date' => now(),
            'renew_date' => now()->addDays(3),
            'is_read' => true,
            'is_active' => false,
            'read_at' => now(),
        ]);
        AquariumNotification::create([
            'aquarium_id' => 2,
            'notification_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'renew_date' => now()->addDays(6),
            'is_read' => false,
            'is_active' => true,
            'read_at' => null,
        ]);
        AquariumNotification::create([
            'aquarium_id' => 2,
            'notification_id' => 2,
            'start_date' => now()->subDays(3),
            'end_date' => now(),
            'renew_date' => now()->addDays(6),
            'is_read' => true,
            'is_active' => true,
            'read_at' => now()->subHours(2),
        ]);
    }
}
