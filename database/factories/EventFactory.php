<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => rand(1,6),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(3),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph(10),
            'published_at' => $this->faker->dateTimeBetween('-2 Week', '-4 day'),
            'location' => $this->faker->address(),
            'start_date' =>  $this->faker->dateTime($format='Y-m-d HH:MM:ss'),
            'status' => $this->faker->randomElement(['open', 'ongoing', 'done']),
        ];
    }
}
