<?php

namespace Database\Factories;

use App\Models\Aquarium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aquarium>
 */
class AquariumFactory extends Factory
{
    protected $model = Aquarium::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10), // Ajuste conforme necessário
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
        ];
    }
}
