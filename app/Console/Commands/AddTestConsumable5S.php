<?php

namespace App\Console\Commands;

use App\Models\Consumable;
use App\Models\Notification;
use App\Models\ConsumableNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddTestConsumable5S extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciano:add-test-consumable-5s';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a test consumable with 5 seconds notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test consumable and notification...');

        // Verificar se já existe
        if (Consumable::where('slug', 'test-consumable-5s')->exists()) {
            $this->error('Test consumable already exists!');
            return 1;
        }

        // Criar o consumable
        $consumable = Consumable::create([
            'name' => 'TEST CONSUMABLE 5S',
            'slug' => 'test-consumable-5s',
            'code' => 'TEST003',
            'description' => 'Test consumable that lasts 5 seconds',
            'image_url' => 'https://app.ciano.pt/images/test_consumable.png',
        ]);

        // Criar a notificação
        $notification = Notification::create([
            'name' => 'Test Consumable 5S',
            'slug' => 'test-consumable-5s',
            'duration_type' => 'seconds',
            'duration_value' => 5,
            'type' => 'single',
            'is_active' => true,
            'is_system' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Criar a relação
        ConsumableNotification::create([
            'consumable_id' => $consumable->id,
            'notification_id' => $notification->id,
        ]);

        // Criar as traduções
        $translations = [
            'en' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Test notification for {aquarium_name}',
                'body' => 'This is a test notification that triggers after 5 seconds.',
            ],
            'pt' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Notificação de teste para {aquarium_name}',
                'body' => 'Esta é uma notificação de teste que dispara após 5 segundos.',
            ],
            'it' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Notifica di test per {aquarium_name}',
                'body' => 'Questa è una notifica di test che si attiva dopo 5 secondi.',
            ],
            'es' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Notificación de prueba para {aquarium_name}',
                'body' => 'Esta es una notificación de prueba que se activa después de 5 segundos.',
            ],
            'de' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Testbenachrichtigung für {aquarium_name}',
                'body' => 'Dies ist eine Testbenachrichtigung, die nach 5 Sekunden ausgelöst wird.',
            ],
            'fr' => [
                'presentation' => 'Test Consumable 5S',
                'title' => 'Notification de test pour {aquarium_name}',
                'body' => 'Cette notification est une notification de test qui se déclenche après 5 secondes.',
            ],
        ];

        foreach ($translations as $locale => $data) {
            $notification->bodies()->create([
                'locale' => $locale,
                'presentation' => $data['presentation'],
                'title' => $data['title'],
                'body' => $data['body'],
            ]);
        }

        $this->info('Test consumable and notification created successfully!');
        return 0;
    }
}
