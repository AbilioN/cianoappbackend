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

        $consumable1 = Consumable::factory()->create([
            'name' => 'Water Conditioner',
            'slug' => 'water-conditioner',
            'code' => 'COM56003',
            'description' => 'Water Conditioner',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification1 = Notification::create([
            'name' => 'Water Conditioner',
            'slug' => 'water-conditioner',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 15,
            'type' => 'single',
        ]);

        $consumableNotification = ConsumableNotification::create([
            'consumable_id' => $consumable1->id,
            'notification_id' => $notification1->id,
        ]);

        $notifications1 = [
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



        foreach ($notifications1 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 1,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable2 = Consumable::factory()->create([
            'name' => 'WATER TEST STRIPS',
            'slug' => 'water-test-strips',
            'code' => 'COM560038',
            'description' => 'Water Test Strips',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification2 = Notification::create([
            'name' => 'Water Test Strips',
            'slug' => 'water-test-strips',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 15,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable2->id,
            'notification_id' => $notification2->id,
        ]);

        $notifications2 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => "Time to check the water quality! Use Ciano® TEST STRIPS to measure the essential water parameters.",
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'È hora de verificar a qualidade da água! Utilize as TEST STRIPS Ciano® para medir os parâmetros essenciais da água.',
            ],
           'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di controllare la qualità dell’acqua! Usa le TEST STRIPS Ciano® per misurare i parametri essenziali dell’acqua.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de verificar la calidad del agua! Usa las TEST STRIPS Ciano® para medir los parámetros esenciales del agua.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, die Wasserqualität zu überprüfen! Verwenden Sie die Ciano® TESTSTREIFEN, um die wichtigsten Wasserparameter zu messen.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de vérifier la qualité de l’eau ! Utilisez les TEST STRIPS Ciano® pour mesurer les paramètres essentiels de l’eau.',
            ],
        ];



        foreach ($notifications2 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 2,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable3 = Consumable::factory()->create([
            'name' => 'WATER CLEAR & PROTECTION S',
            'slug' => slugify('WATER CLEAR & PROTECTION S'),
            'code' => 'COM560018',
            'description' => 'Water Clear & Protection S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification3 = Notification::create([
            'name' => 'Water clear and protection S',
            'slug' => 'water-clear-and-protection-s',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 30,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable3->id,
            'notification_id' => $notification3->id,
        ]);

        $notifications3 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to replace the WATER CLEAR & PROTECTION to keep your aquarium water crystal clear and fresh.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de substituir o WATER CLEAR & PROTECTION e manter a água do seu aquário cristalina e sem odores.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di sostituire il WATER CLEAR & PROTECTION e mantenere l’acqua del tuo acquario cristallina e senza odori.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => 'Es hora de reemplazar el WATER CLEAR & PROTECTION y mantener el agua de tu acuario cristalina y sin olores.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das WATER CLEAR & PROTECTION auszutauschen und das Wasser in Ihrem Aquarium kristallklar und geruchsfrei zu halten.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de remplacer le WATER CLEAR & PROTECTION et de garder l’eau de votre aquarium cristalline et sans odeurs.',
            ],
        ];



        foreach ($notifications3 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 3,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }


        $consumable4 = Consumable::factory()->create([
            'name' => 'WATER CLEAR & PROTECTION M',
            'slug' => slugify('WATER CLEAR & PROTECTION M'),
            'code' => 'COM560022',
            'description' => 'Water Clear & Protection M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification4 = Notification::create([
            'name' => 'Water clear and protection M',
            'slug' => 'water-clear-and-protection-m',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 30,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable4->id,
            'notification_id' => $notification4->id,
        ]);

        $notifications4 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to replace the WATER CLEAR & PROTECTION to keep your aquarium water crystal clear and fresh.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de substituir o WATER CLEAR & PROTECTION e manter a água do seu aquário cristalina e sem odores.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di sostituire il WATER CLEAR & PROTECTION e mantenere l’acqua del tuo acquario cristallina e senza odori.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => 'Es hora de reemplazar el WATER CLEAR & PROTECTION y mantener el agua de tu acuario cristalina y sin olores.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das WATER CLEAR & PROTECTION auszutauschen und das Wasser in Ihrem Aquarium kristallklar und geruchsfrei zu halten.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de remplacer le WATER CLEAR & PROTECTION et de garder l’eau de votre aquarium cristalline et sans odeurs.',
            ],
        ];



        foreach ($notifications4 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 4,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable5 = Consumable::factory()->create([
            'name' => 'WATER CLEAR & PROTECTION L',
            'slug' => slugify('WATER CLEAR & PROTECTION L'),
            'code' => 'COM560028',
            'description' => 'Water Clear & Protection L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification5 = Notification::create([
            'name' => 'Water clear and protection L',
            'slug' => 'water-clear-and-protection-l',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 30,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable5->id,
            'notification_id' => $notification5->id,
        ]);

        $notifications5 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to replace the WATER CLEAR & PROTECTION to keep your aquarium water crystal clear and fresh.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de substituir o WATER CLEAR & PROTECTION e manter a água do seu aquário cristalina e sem odores.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di sostituire il WATER CLEAR & PROTECTION e mantenere l’acqua del tuo acquario cristallina e senza odori.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => 'Es hora de reemplazar el WATER CLEAR & PROTECTION y mantener el agua de tu acuario cristalina y sin olores.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das WATER CLEAR & PROTECTION auszutauschen und das Wasser in Ihrem Aquarium kristallklar und geruchsfrei zu halten.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de remplacer le WATER CLEAR & PROTECTION et de garder l’eau de votre aquarium cristalline et sans odeurs.',
            ],
        ];



        foreach ($notifications5 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 5,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        
        $consumable6 = Consumable::factory()->create([
            'name' => 'WATER CLEAR & PROTECTION XL',
            'slug' => slugify('WATER CLEAR & PROTECTION XL'),
            'code' => 'COM560051',
            'description' => 'Water Clear & Protection XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification6 = Notification::create([
            'name' => 'Water clear and protection XL',
            'slug' => 'water-clear-and-protection-xl',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 30,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable6->id,
            'notification_id' => $notification6->id,
        ]);

        $notifications6 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to replace the WATER CLEAR & PROTECTION to keep your aquarium water crystal clear and fresh.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de substituir o WATER CLEAR & PROTECTION e manter a água do seu aquário cristalina e sem odores.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di sostituire il WATER CLEAR & PROTECTION e mantenere l’acqua del tuo acquario cristallina e senza odori.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => 'Es hora de reemplazar el WATER CLEAR & PROTECTION y mantener el agua de tu acuario cristalina y sin olores.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das WATER CLEAR & PROTECTION auszutauschen und das Wasser in Ihrem Aquarium kristallklar und geruchsfrei zu halten.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de remplacer le WATER CLEAR & PROTECTION et de garder l’eau de votre aquarium cristalline et sans odeurs.',
            ],
        ];



        foreach ($notifications6 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 6,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable7= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT S',
            'slug' => slugify('WATER BIO-BACT S'),
            'code' => 'COM560019',
            'description' => 'Water bio-bact S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification7 = Notification::create([
            'name' => 'Water Bio-Bact S',
            'slug' => 'water-bio-bact-s',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 140,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable7->id,
            'notification_id' => $notification7->id,
        ]);

        $notifications7 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];



        foreach ($notifications7 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 7,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable8= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT M',
            'slug' => slugify('WATER BIO-BACT M'),
            'code' => 'COM560023',
            'description' => 'Water bio-bact M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification8 = Notification::create([
            'name' => 'Water Bio-Bact M',
            'slug' => 'water-bio-bact-m',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 140,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable8->id,
            'notification_id' => $notification8->id,
        ]);

        $notifications8 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];



        foreach ($notifications8 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 8,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable9= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT L',
            'slug' => slugify('WATER BIO-BACT L'),
            'code' => 'COM560029',
            'description' => 'Water bio-bact L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification9 = Notification::create([
            'name' => 'Water Bio-Bact L',
            'slug' => 'water-bio-bact-l',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 140,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable9->id,
            'notification_id' => $notification9->id,
        ]);

        $notifications9 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications9 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 9,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable10= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT XL',
            'slug' => slugify('WATER BIO-BACT XL'),
            'code' => 'COM560052',
            'description' => 'Water bio-bact XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification10 = Notification::create([
            'name' => 'Water Bio-Bact XL',
            'slug' => 'water-bio-bact-xl',
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 140,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable10->id,
            'notification_id' => $notification10->id,
        ]);

        $notifications10 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications10 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 10,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable11= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT S (Pack)',
            'slug' => slugify('WATER BIO-BACT S (Pack)'),
            'code' => 'COM560033',
            'description' => 'Water bio-bact S (pack)',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification11 = Notification::create([
            'name' => 'Water Bio-Bact S (pack)',
            'slug' => slugify('Water Bio-Bact S (pack)'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable11->id,
            'notification_id' => $notification11->id,
        ]);

        $notifications11 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications11 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 11,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable12= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT M (Pack)',
            'slug' => slugify('WATER BIO-BACT M (Pack)'),
            'code' => 'COM560034',
            'description' => 'Water bio-bact M (pack)',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification12 = Notification::create([
            'name' => 'Water Bio-Bact M (pack)',
            'slug' => slugify('Water Bio-Bact M (pack)'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable12->id,
            'notification_id' => $notification12->id,
        ]);

        $notifications12 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications12 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 12,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable13= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT L (Pack)',
            'slug' => slugify('WATER BIO-BACT L (Pack)'),
            'code' => 'COM560036',
            'description' => 'Water bio-bact L (pack)',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification13 = Notification::create([
            'name' => 'Water Bio-Bact L (pack)',
            'slug' => slugify('Water Bio-Bact L (pack)'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable13->id,
            'notification_id' => $notification13->id,
        ]);

        $notifications13 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications13 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 13,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable14= Consumable::factory()->create([
            'name' => 'WATER BIO-BACT XL (Pack)',
            'slug' => slugify('WATER BIO-BACT XL (Pack)'),
            'code' => 'COM560064',
            'description' => 'Water bio-bact XL (pack)',
            'image_url' => 'https://via.placeholder.com/150',
        ]);



        $notification14 = Notification::create([
            'name' => 'Water Bio-Bact XL (pack)',
            'slug' => slugify('Water Bio-Bact XL (pack)'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable14->id,
            'notification_id' => $notification14->id,
        ]);

        $notifications14 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Time to maintain the biological balance of your aquarium! Replace the WATER BIO-BACT and ensure effective biological filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Está na hora de manter o equilíbrio biológico do seu aquário! Substitua o WATER BIO-BACT e  e mantenha a eficácia da filtragem biológica.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'È ora di mantenere l’equilibrio biologico del tuo acquario! Sostituisci il WATER BIO-BACT e mantieni l’efficacia della filtrazione biologica.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Es hora de mantener el equilibrio biológico de tu acuario! Reemplaza el WATER BIO-BACT y conserva la eficacia de la filtración biológica.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Es ist Zeit, das biologische Gleichgewicht in Ihrem Aquarium zu erhalten! Ersetzen Sie das WATER BIO-BACT und bewahren Sie die Effizienz der biologischen Filterung.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Il est temps de maintenir l’équilibre biologique de votre aquarium ! Remplacez le WATER BIO-BACT et préservez l’efficacité de la filtration biologique.',
            ],
        ];


        foreach ($notifications14 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 14,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        
        $consumable15= Consumable::factory()->create([
            'name' => 'WATER FOAM S',
            'slug' => slugify('WATER FOAM S'),
            'code' => 'COM560021',
            'description' => 'Water Foam S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification15 = Notification::create([
            'name' => 'Water Foam S',
            'slug' => slugify('Water Foam S'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable15->id,
            'notification_id' => $notification15->id,
        ]);

        $notifications15 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER FOAM? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER FOAM? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER FOAM? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER FOAM? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER FOAM erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER FOAM ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications15 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 15,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        
        $consumable16= Consumable::factory()->create([
            'name' => 'WATER FOAM M',
            'slug' => slugify('WATER FOAM M'),
            'code' => 'COM560025',
            'description' => 'Water Foam M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification16 = Notification::create([
            'name' => 'Water Foam M',
            'slug' => slugify('Water Foam M'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable16->id,
            'notification_id' => $notification16->id,
        ]);

        $notifications16 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER FOAM? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER FOAM? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER FOAM? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER FOAM? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER FOAM erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER FOAM ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications16 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 16,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable17 = Consumable::factory()->create([
            'name' => 'WATER FOAM L',
            'slug' => slugify('WATER FOAM L'),
            'code' => 'COM560026',
            'description' => 'Water Foam L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification17 = Notification::create([
            'name' => 'Water Foam L',
            'slug' => slugify('Water Foam L'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable17->id,
            'notification_id' => $notification17->id,
        ]);

        $notifications17 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER FOAM? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER FOAM? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER FOAM? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER FOAM? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER FOAM erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER FOAM ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications17 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 17,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable18 = Consumable::factory()->create([
            'name' => 'WATER FOAM XL',
            'slug' => slugify('WATER FOAM XL'),
            'code' => 'COM560054',
            'description' => 'Water Foam XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification18 = Notification::create([
            'name' => 'Water Foam XL',
            'slug' => slugify('Water Foam XL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable18->id,
            'notification_id' => $notification18->id,
        ]);

        $notifications18 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER FOAM? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER FOAM? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER FOAM? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER FOAM? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER FOAM erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER FOAM ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications18 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 18,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable19 = Consumable::factory()->create([
            'name' => 'WATER FOAM COARSE XL',
            'slug' => slugify('WATER FOAM COARSE XL'),
            'code' => 'COM560053',
            'description' => 'Water Foam Coarse XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification19 = Notification::create([
            'name' => 'Water Foam Coarse XL',
            'slug' => slugify('Water Foam Coarse XL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 90,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable19->id,
            'notification_id' => $notification19->id,
        ]);

        $notifications19 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER FOAM COARSE? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER FOAM COARSE? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER FOAM COARSE? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER FOAM COARSE? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER FOAM COARSE erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER FOAM COARSE ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications19 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 19,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable20 = Consumable::factory()->create([
            'name' => 'WATER PAD XL',
            'slug' => slugify('WATER PAD XL'),
            'code' => 'COM560055',
            'description' => 'Water Pad XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification20 = Notification::create([
            'name' => 'Water Pad XL',
            'slug' => slugify('Water Pad XL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 15,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable20->id,
            'notification_id' => $notification20->id,
        ]);

        $notifications20 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Ready to renew the WATER PAD? This will ensure clean water and efficient filtration.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Vamos renovar o WATER PAD? Assim garantirá uma água limpa e uma filtração eficiente.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Rinnoviamo il WATER PAD? In questo modo garantirai un’acqua pulita e una filtrazione efficiente.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Renovamos el WATER PAD? Así garantizarás agua limpia y una filtración eficiente.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Sollen wir den WATER PAD erneuern? So stellen Sie sauberes Wasser und eine effiziente Filterung sicher.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'On renouvelle le WATER PAD ? Cela garantira une eau propre et une filtration efficace.',
            ],
        ];


        foreach ($notifications20 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 20,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable21 = Consumable::factory()->create([
            'name' => 'WATER ALGAE S',
            'slug' => slugify('WATER ALGAE S'),
            'code' => 'COM560020',
            'description' => 'Water Algae S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification21 = Notification::create([
            'name' => 'Water Algae S',
            'slug' => slugify('Water Algae S'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 45,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable21->id,
            'notification_id' => $notification21->id,
        ]);

        $notifications21 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Battling algae in your aquarium? Follow the guide’s steps and replace the WATER ALGAE if needed.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Ainda com algas no aquário? Siga os passos do guia e se necessário substitua o WATER ALGAE.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Ancora alghe nell’acquario? Segui i passaggi della guida e, se necessario, sostituisci il WATER ALGAE.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Aún con algas en el acuario? Sigue los pasos de la guía y, si es necesario, reemplaza el WATER ALGAE.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Immer noch Algen im Aquarium? Befolgen Sie die Schritte im Leitfaden und ersetzen Sie bei Bedarf das WATER ALGAE.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Toujours des algues dans l’aquarium ? Suivez les étapes du guide et, si nécessaire, remplacez le WATER ALGAE.',
            ],
        ];


        foreach ($notifications21 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 21,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable22 = Consumable::factory()->create([
            'name' => 'WATER ALGAE M',
            'slug' => slugify('WATER ALGAE M'),
            'code' => 'COM560024',
            'description' => 'Water Algae M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification22 = Notification::create([
            'name' => 'Water Algae M',
            'slug' => slugify('Water Algae M'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 45,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable22->id,
            'notification_id' => $notification22->id,
        ]);

        $notifications22 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Battling algae in your aquarium? Follow the guide’s steps and replace the WATER ALGAE if needed.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Ainda com algas no aquário? Siga os passos do guia e se necessário substitua o WATER ALGAE.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Ancora alghe nell’acquario? Segui i passaggi della guida e, se necessario, sostituisci il WATER ALGAE.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Aún con algas en el acuario? Sigue los pasos de la guía y, si es necesario, reemplaza el WATER ALGAE.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Immer noch Algen im Aquarium? Befolgen Sie die Schritte im Leitfaden und ersetzen Sie bei Bedarf das WATER ALGAE.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Toujours des algues dans l’aquarium ? Suivez les étapes du guide et, si nécessaire, remplacez le WATER ALGAE.',
            ],
        ];


        foreach ($notifications22 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 22,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable23 = Consumable::factory()->create([
            'name' => 'WATER ALGAE L',
            'slug' => slugify('WATER ALGAE L'),
            'code' => 'COM560030',
            'description' => 'Water Algae L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification23 = Notification::create([
            'name' => 'Water Algae L',
            'slug' => slugify('Water Algae L'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 45,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable23->id,
            'notification_id' => $notification23->id,
        ]);

        $notifications23 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Battling algae in your aquarium? Follow the guide’s steps and replace the WATER ALGAE if needed.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Ainda com algas no aquário? Siga os passos do guia e se necessário substitua o WATER ALGAE.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Ancora alghe nell’acquario? Segui i passaggi della guida e, se necessario, sostituisci il WATER ALGAE.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Aún con algas en el acuario? Sigue los pasos de la guía y, si es necesario, reemplaza el WATER ALGAE.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Immer noch Algen im Aquarium? Befolgen Sie die Schritte im Leitfaden und ersetzen Sie bei Bedarf das WATER ALGAE.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Toujours des algues dans l’aquarium ? Suivez les étapes du guide et, si nécessaire, remplacez le WATER ALGAE.',
            ],
        ];


        foreach ($notifications23 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 23,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable24 = Consumable::factory()->create([
            'name' => 'WATER ALGAE xL',
            'slug' => slugify('WATER ALGAE xL'),
            'code' => 'TRE400005',
            'description' => 'Water Algae xL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification24 = Notification::create([
            'name' => 'Water Algae xL',
            'slug' => slugify('Water Algae xL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 45,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable24->id,
            'notification_id' => $notification24->id,
        ]);

        $notifications24 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Battling algae in your aquarium? Follow the guide’s steps and replace the WATER ALGAE if needed.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Ainda com algas no aquário? Siga os passos do guia e se necessário substitua o WATER ALGAE.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Ancora alghe nell’acquario? Segui i passaggi della guida e, se necessario, sostituisci il WATER ALGAE.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¿Aún con algas en el acuario? Sigue los pasos de la guía y, si es necesario, reemplaza el WATER ALGAE.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Immer noch Algen im Aquarium? Befolgen Sie die Schritte im Leitfaden und ersetzen Sie bei Bedarf das WATER ALGAE.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Toujours des algues dans l’aquarium ? Suivez les étapes du guide et, si nécessaire, remplacez le WATER ALGAE.',
            ],
        ];


        foreach ($notifications24 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 24,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }

        
        $consumable25 = Consumable::factory()->create([
            'name' => 'FISH PROTECTION DOSATOR S',
            'slug' => slugify('FISH PROTECTION DOSATOR S'),
            'code' => 'COM560043',
            'description' => 'Fish Protection Dosator S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification25 = Notification::create([
            'name' => 'Fish Protection Dosator S',
            'slug' => slugify('Fish Protection Dosator S'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable25->id,
            'notification_id' => $notification25->id,
        ]);

        $notifications25 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, fish lovers! Replace the FISH PROTECTION DOSATOR to boost your fish’s immune system and protect them from diseases.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta PET! Substitua o FISH PROTECTION DOSATOR e continue a reforçar o sistema imunitário do seu Peixe protejendo-o contra doenças. ',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta PET! Sostituisci il FISH PROTECTION DOSATOR e continua a rafforzare il sistema immunitario del tuo pesce proteggendolo dalle malattie.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta PET! Sustituye el FISH PROTECTION DOSATOR y sigue reforzando el sistema inmunitario de tu pez protegiéndolo contra enfermedades.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'PET-Alarm! Ersetzen Sie den FISH PROTECTION DOSATOR und stärken Sie weiterhin das Immunsystem Ihres Fisches, um ihn vor Krankheiten zu schützen.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte PET ! Remplacez le FISH PROTECTION DOSATOR et continuez à renforcer le système immunitaire de votre poisson en le protégeant contre les maladies.',
            ],
        ];


        foreach ($notifications25 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 25,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        
        $consumable26 = Consumable::factory()->create([
            'name' => 'FISH PROTECTION DOSATOR M',
            'slug' => slugify('FISH PROTECTION DOSATOR M'),
            'code' => 'COM560044',
            'description' => 'Fish Protection Dosator M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification26 = Notification::create([
            'name' => 'Fish Protection Dosator M',
            'slug' => slugify('Fish Protection Dosator M'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable26->id,
            'notification_id' => $notification26->id,
        ]);

        $notifications26 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, fish lovers! Replace the FISH PROTECTION DOSATOR to boost your fish’s immune system and protect them from diseases.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta PET! Substitua o FISH PROTECTION DOSATOR e continue a reforçar o sistema imunitário do seu Peixe protejendo-o contra doenças. ',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta PET! Sostituisci il FISH PROTECTION DOSATOR e continua a rafforzare il sistema immunitario del tuo pesce proteggendolo dalle malattie.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta PET! Sustituye el FISH PROTECTION DOSATOR y sigue reforzando el sistema inmunitario de tu pez protegiéndolo contra enfermedades.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'PET-Alarm! Ersetzen Sie den FISH PROTECTION DOSATOR und stärken Sie weiterhin das Immunsystem Ihres Fisches, um ihn vor Krankheiten zu schützen.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte PET ! Remplacez le FISH PROTECTION DOSATOR et continuez à renforcer le système immunitaire de votre poisson en le protégeant contre les maladies.',
            ],
        ];


        foreach ($notifications26 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 26,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }


        $consumable27 = Consumable::factory()->create([
            'name' => 'FISH PROTECTION DOSATOR L',
            'slug' => slugify('FISH PROTECTION DOSATOR L'),
            'code' => 'COM560049',
            'description' => 'Fish Protection Dosator L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification27 = Notification::create([
            'name' => 'Fish Protection Dosator L',
            'slug' => slugify('Fish Protection Dosator L'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable27->id,
            'notification_id' => $notification27->id,
        ]);

        $notifications27 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, fish lovers! Replace the FISH PROTECTION DOSATOR to boost your fish’s immune system and protect them from diseases.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta PET! Substitua o FISH PROTECTION DOSATOR e continue a reforçar o sistema imunitário do seu Peixe protejendo-o contra doenças. ',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta PET! Sostituisci il FISH PROTECTION DOSATOR e continua a rafforzare il sistema immunitario del tuo pesce proteggendolo dalle malattie.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta PET! Sustituye el FISH PROTECTION DOSATOR y sigue reforzando el sistema inmunitario de tu pez protegiéndolo contra enfermedades.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'PET-Alarm! Ersetzen Sie den FISH PROTECTION DOSATOR und stärken Sie weiterhin das Immunsystem Ihres Fisches, um ihn vor Krankheiten zu schützen.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte PET ! Remplacez le FISH PROTECTION DOSATOR et continuez à renforcer le système immunitaire de votre poisson en le protégeant contre les maladies.',
            ],
        ];


        foreach ($notifications27 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 27,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable28 = Consumable::factory()->create([
            'name' => 'FISH PROTECTION DOSATOR XL',
            'slug' => slugify('FISH PROTECTION DOSATOR XL'),
            'code' => 'COM560045',
            'description' => 'Fish Protection Dosator XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification28 = Notification::create([
            'name' => 'Fish Protection Dosator XL',
            'slug' => slugify('Fish Protection Dosator XL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable28->id,
            'notification_id' => $notification28->id,
        ]);

        $notifications28 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, fish lovers! Replace the FISH PROTECTION DOSATOR to boost your fish’s immune system and protect them from diseases.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta PET! Substitua o FISH PROTECTION DOSATOR e continue a reforçar o sistema imunitário do seu Peixe protejendo-o contra doenças. ',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta PET! Sostituisci il FISH PROTECTION DOSATOR e continua a rafforzare il sistema immunitario del tuo pesce proteggendolo dalle malattie.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta PET! Sustituye el FISH PROTECTION DOSATOR y sigue reforzando el sistema inmunitario de tu pez protegiéndolo contra enfermedades.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'PET-Alarm! Ersetzen Sie den FISH PROTECTION DOSATOR und stärken Sie weiterhin das Immunsystem Ihres Fisches, um ihn vor Krankheiten zu schützen.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte PET ! Remplacez le FISH PROTECTION DOSATOR et continuez à renforcer le système immunitaire de votre poisson en le protégeant contre les maladies.',
            ],
        ];


        foreach ($notifications28 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 28,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable29 = Consumable::factory()->create([
            'name' => 'PLANTS PROTECTION DOSATOR S',
            'slug' => slugify('PLANTS PROTECTION DOSATOR S'),
            'code' => 'COM560046',
            'description' => 'Plants Protection Dosator S',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification29 = Notification::create([
            'name' => 'Plants Protection Dosator S',
            'slug' => slugify('Plants Protection Dosator S'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable29->id,
            'notification_id' => $notification29->id,
        ]);

        $notifications29 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, plant enthusiasts! Replace the PLANTS PROTECTION DOSATOR to feed your plants with essential nutrients for their healthy growth and well-being.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta plantas! Substitua o PLANTS PROTECTION DOSATOR e continue a alimentar as suas plantas com nutrientes essenciais para o seu crescimento e bem estar.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta piante! Sostituisci il PLANTS PROTECTION DOSATOR e continua a nutrire le tue piante con i nutrienti essenziali per la loro crescita e il loro benessere.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta plantas! Sustituye el PLANTS PROTECTION DOSATOR y sigue alimentando tus plantas con nutrientes esenciales para su crecimiento y bienestar.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Pflanzen-Alarm! Ersetzen Sie den PLANTS PROTECTION DOSATOR und versorgen Sie Ihre Pflanzen weiterhin mit wichtigen Nährstoffen für ihr Wachstum und Wohlbefinden.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte plantes ! Remplacez le PLANTS PROTECTION DOSATOR et continuez à nourrir vos plantes avec les nutriments essentiels à leur croissance et leur bien-être.',
            ],
        ];


        foreach ($notifications29 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 29,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable30 = Consumable::factory()->create([
            'name' => 'PLANTS PROTECTION DOSATOR M',
            'slug' => slugify('PLANTS PROTECTION DOSATOR M'),
            'code' => 'COM560047',
            'description' => 'Plants Protection Dosator M',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification30 = Notification::create([
            'name' => 'Plants Protection Dosator M',
            'slug' => slugify('Plants Protection Dosator M'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable30->id,
            'notification_id' => $notification30->id,
        ]);

        $notifications30 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, plant enthusiasts! Replace the PLANTS PROTECTION DOSATOR to feed your plants with essential nutrients for their healthy growth and well-being.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta plantas! Substitua o PLANTS PROTECTION DOSATOR e continue a alimentar as suas plantas com nutrientes essenciais para o seu crescimento e bem estar.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta piante! Sostituisci il PLANTS PROTECTION DOSATOR e continua a nutrire le tue piante con i nutrienti essenziali per la loro crescita e il loro benessere.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta plantas! Sustituye el PLANTS PROTECTION DOSATOR y sigue alimentando tus plantas con nutrientes esenciales para su crecimiento y bienestar.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Pflanzen-Alarm! Ersetzen Sie den PLANTS PROTECTION DOSATOR und versorgen Sie Ihre Pflanzen weiterhin mit wichtigen Nährstoffen für ihr Wachstum und Wohlbefinden.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte plantes ! Remplacez le PLANTS PROTECTION DOSATOR et continuez à nourrir vos plantes avec les nutriments essentiels à leur croissance et leur bien-être.',
            ],
        ];


        foreach ($notifications30 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 30,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable31 = Consumable::factory()->create([
            'name' => 'PLANTS PROTECTION DOSATOR L',
            'slug' => slugify('PLANTS PROTECTION DOSATOR L'),
            'code' => 'COM560050',
            'description' => 'Plants Protection Dosator L',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification31 = Notification::create([
            'name' => 'Plants Protection Dosator L',
            'slug' => slugify('Plants Protection Dosator L'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable31->id,
            'notification_id' => $notification31->id,
        ]);

        $notifications31 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, plant enthusiasts! Replace the PLANTS PROTECTION DOSATOR to feed your plants with essential nutrients for their healthy growth and well-being.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta plantas! Substitua o PLANTS PROTECTION DOSATOR e continue a alimentar as suas plantas com nutrientes essenciais para o seu crescimento e bem estar.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta piante! Sostituisci il PLANTS PROTECTION DOSATOR e continua a nutrire le tue piante con i nutrienti essenziali per la loro crescita e il loro benessere.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta plantas! Sustituye el PLANTS PROTECTION DOSATOR y sigue alimentando tus plantas con nutrientes esenciales para su crecimiento y bienestar.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Pflanzen-Alarm! Ersetzen Sie den PLANTS PROTECTION DOSATOR und versorgen Sie Ihre Pflanzen weiterhin mit wichtigen Nährstoffen für ihr Wachstum und Wohlbefinden.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte plantes ! Remplacez le PLANTS PROTECTION DOSATOR et continuez à nourrir vos plantes avec les nutriments essentiels à leur croissance et leur bien-être.',
            ],
        ];


        foreach ($notifications31 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 31,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
        
        $consumable32 = Consumable::factory()->create([
            'name' => 'PLANTS PROTECTION DOSATOR XL',
            'slug' => slugify('PLANTS PROTECTION DOSATOR XL'),
            'code' => 'COM560048',
            'description' => 'Plants Protection Dosator XL',
            'image_url' => 'https://via.placeholder.com/150',
        ]);

        $notification32 = Notification::create([
            'name' => 'Plants Protection Dosator XL',
            'slug' => slugify('Plants Protection Dosator XL'),
            // 'duration_type' => 'days',
            'duration_type' => 'seconds',
            'duration_value' => 60,
            'type' => 'single',
        ]);

        ConsumableNotification::create([
            'consumable_id' => $consumable32->id,
            'notification_id' => $notification32->id,
        ]);

        $notifications32 = [
            'en' => [
                'title' => 'Your aquarium {aquarium_name} needs your attention!',
                'body' => 'Attention, plant enthusiasts! Replace the PLANTS PROTECTION DOSATOR to feed your plants with essential nutrients for their healthy growth and well-being.',
            ],
            'pt' => [
                'title' => 'O seu aquário {aquarium_name} precisa da sua atenção!',
                'body' => 'Alerta plantas! Substitua o PLANTS PROTECTION DOSATOR e continue a alimentar as suas plantas com nutrientes essenciais para o seu crescimento e bem estar.',
            ],
            'it' => [
                'title' => 'Il tuo acquario {aquarium_name} ha bisogno della tua attenzione!',
                'body' => 'Allerta piante! Sostituisci il PLANTS PROTECTION DOSATOR e continua a nutrire le tue piante con i nutrienti essenziali per la loro crescita e il loro benessere.',
            ],
            'es' => [
                'title' => '¡Tu acuario {aquarium_name} necesita tu atención!',
                'body' => '¡Alerta plantas! Sustituye el PLANTS PROTECTION DOSATOR y sigue alimentando tus plantas con nutrientes esenciales para su crecimiento y bienestar.',
            ],
            'de' => [
                'title' => 'Ihr Aquarium {aquarium_name} braucht Ihre Aufmerksamkeit!',
                'body' => 'Pflanzen-Alarm! Ersetzen Sie den PLANTS PROTECTION DOSATOR und versorgen Sie Ihre Pflanzen weiterhin mit wichtigen Nährstoffen für ihr Wachstum und Wohlbefinden.',
            ],
            'fr' => [
                'title' => 'Votre aquarium {aquarium_name} a besoin de votre attention !',
                'body' => 'Alerte plantes ! Remplacez le PLANTS PROTECTION DOSATOR et continuez à nourrir vos plantes avec les nutriments essentiels à leur croissance et leur bien-être.',
            ],
        ];


        foreach ($notifications32 as $lang => $notification) {
            NotificationBody::create([
                'notification_id' => 32,
                'lang' => $lang,
                'title' => $notification['title'],
                'body' => $notification['body'],
            ]);
        }
    }
}
