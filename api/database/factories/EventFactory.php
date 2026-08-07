<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = $this->faker->dateTimeBetween('-1 month', '+3 months');
        $title = rtrim($this->faker->sentence(3), '.');

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'category' => $this->faker->randomElement(['enogastronomia', 'cultura', 'sport', 'musica']),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 hours'),
            'is_published' => $this->faker->boolean(80),
        ];
    }
}
