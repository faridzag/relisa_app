<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $categories = ['pendidikan', 'lingkungan', 'kesehatan'];
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement($this->categories),
            'slug' => Str::slug($this->faker->randomElement($this->categories)),
        ];
    }
}
