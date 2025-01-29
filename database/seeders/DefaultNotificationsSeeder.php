<?php

namespace Database\Seeders;

use App\Models\Aquarium;
use App\Models\Consumable;
use App\Models\ConsumableNotification;
use App\Models\Notification;
use App\Models\NotificationBody;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'default@email.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);
        $mainAquarium1 = Aquarium::factory()->create([
            'user_id' => $user->id,
            'name' => 'Main Aquarium 1',
            'slug' => 'main-aquarium-1',
        ]);

        $mainAquarium2 = Aquarium::factory()->create([
            'user_id' => $user->id,
            'name' => 'Main Aquarium 2',
            'slug' => 'main-aquarium-2',
        ]);

        $consumable = Consumable::factory()->create([
            'name' => 'Water Conditioner',
            'slug' => 'water-conditioner',
            'code' => 'COM560038',
            'description' => 'Water Conditioner',
            'image_url' => 'https://picsum.photos/200/300',
        ]);



        $notification = Notification::create([
            'name' => 'Water Conditioner',
            'slug' => 'water-conditioner',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 15,
            'type' => 'single',
        ]);

        $consumableNotification = ConsumableNotification::create([
            'consumable_id' => $consumable->id,
            'notification_id' => $notification->id,
        ]);

        $notifications = [
            'en' => [
                'title' => 'Your Aquarium {aquarium_name} Needs Your Attention',
                'body' => "It's time to take care of your aquarium's water! Add WATER CONDITIONER and WATER BIO-BACT to protect the water, eliminate impurities, and maintain the ideal biological balance for your fish.",
            ],
            'pt' => [
                'title' => 'Seu Aquário {aquarium_name} precisa da sua atenção',
                'body' => 'Esta na hora de cuidar da água do seu aquário! /n Adicione o líquido WATER CONDITIONER e WATER BIO-BACT para proteger a água, eliminar impurezas e manter o equilíbrio biológico ideal para seus peixes.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione',
                'body' => 'È ora di prendersi cura dell\'acqua del tuo acquario! Aggiungi WATER CONDITIONER e WATER BIO-BACT per proteggere l\'acqua, eliminare le impurità e mantenere l\'equilibrio biologico ideale per i tuoi pesci.',
            ],
            'es' => [
                'title' => 'Tu Acuario {aquarium_name} necesita tu atención',
                'body' => '¡Es hora de cuidar el agua de tu acuario! Agrega WATER CONDITIONER y WATER BIO-BACT para proteger el agua, eliminar impurezas e mantener el equilibrio biológico ideal para tus peces.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} benötigt Ihre Aufmerksamkeit',
                'body' => 'Es ist Zeit, sich um das Wasser deines Aquariums zu kümmern! Füge WATER CONDITIONER und WATER BIO-BACT hinzu, um das Wasser zu schützen, Verunreinigungen zu beseitigen und das ideale biologische Gleichgewicht für deine Fische aufrechtzuerhalten.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention',
                'body' => 'Il est temps de prendre soin de l\'eau de votre aquarium ! Ajoutez WATER CONDITIONER et WATER BIO-BACT pour protéger l\'eau, éliminer les impuretés et maintenir l\'équilibre biologique idéal pour vos poissons.',
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
