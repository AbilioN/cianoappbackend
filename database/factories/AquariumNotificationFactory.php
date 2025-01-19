<?php

namespace Database\Factories;

use App\Models\AquariumNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AquariumNotification>
 */
class AquariumNotificationFactory extends Factory
{
    protected $model = AquariumNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'aquarium_id' => $this->faker->numberBetween(1, 2), // Ajuste conforme necessário
            'notification_id' => $this->faker->numberBetween(1, 2), // Ajuste conforme necessário
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'renew_date' => now()->addDays(6),
            'is_read' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
            'read_at' => $this->faker->optional()->dateTime,
        ];
    }
}
