<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Aquarium;
use App\Models\AquariumNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }


    public function test_create_aquarium()
    {
        // $response = $this->post('/api/aquariums', [
        //     'name' => 'Aquarium 1',
        // ]);
        
        $user = User::factory()->create(['role_id' => 2]);
        $aquarium = Aquarium::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $notification = Notification::factory()->create();
        $aquariumNotification = AquariumNotification::factory()->create([
            'aquarium_id' => $aquarium->id,
            'notification_id' => $notification->id,
        ]);
        $aquariumNotification2 = AquariumNotification::factory()->create([
            'aquarium_id' => $aquarium->id,
            'notification_id' => $notification->id,
        ]);

        $aquarium->refresh();    

        dd($notification->aquariumNotifications);

    }
}
