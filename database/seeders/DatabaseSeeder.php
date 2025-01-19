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

        // Criar aquários
        $aquarium1 = Aquarium::factory()->create([
            'user_id' => 2,
        ]);
        $aquarium2 = Aquarium::factory()->create([
            'user_id' => 2,
        ]);


        // Criar notificações
        $notification1 = Notification::factory()->create();
        $notification2 = Notification::factory()->create();

        // Criar 20 notificações de aquário para cada aquário usando factories
        for ($i = 0; $i < 15; $i++) {
            AquariumNotification::factory()->create([
                'aquarium_id' => $aquarium1->id,
                'notification_id' => $notification1->id,
                'start_date' => now()->addDays($i),
                'end_date' => now()->addDays($i + 3),
                'renew_date' => now()->addDays($i + 6),
                'is_read' => false,
                'is_active' => true,
                'read_at' => null,
            ]);

            AquariumNotification::factory()->create([
                'aquarium_id' => $aquarium1->id,
                'notification_id' => $notification2->id,
                'start_date' => now()->subDays($i),
                'end_date' => now()->addDays($i),
                'renew_date' => now()->addDays($i + 3),
                'is_read' => true,
                'is_active' => false,
                'read_at' => now(),
            ]);

            AquariumNotification::factory()->create([
                'aquarium_id' => $aquarium2->id,
                'notification_id' => $notification1->id,
                'start_date' => now()->addDays($i),
                'end_date' => now()->addDays($i + 3),
                'renew_date' => now()->addDays($i + 6),
                'is_read' => false,
                'is_active' => true,
                'read_at' => null,
            ]);

            AquariumNotification::factory()->create([
                'aquarium_id' => $aquarium2->id,
                'notification_id' => $notification2->id,
                'start_date' => now()->subDays($i),
                'end_date' => now(),
                'renew_date' => now()->addDays($i + 6),
                'is_read' => true,
                'is_active' => true,
                'read_at' => now()->subHours(2),
            ]);
        }

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
