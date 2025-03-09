<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
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
        return [
            'event_name' => $this->faker->unique()->name(),
            'event_slug' => $this->faker->unique()->slug(),
            'event_description' => $this->faker->text(),
            'event_image' => $this->faker->imageUrl($width = 640, $height = 480),
            'event_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'event_place' => $this->faker->streetName(),
        ];
    }
}
