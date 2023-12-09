<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = Category::pluck('id');

        $sizes = ['S', 'M', 'L', 'XL', 'XL', 'XXL'];

        return [
            'category_id' => fake()->randomElement($categoryIds),
            'name' => fake()->name(),
            'price' => rand(25, 1000),
            'color' => fake()->colorName(),
            'size' => fake()->randomElement($sizes),
        ];
    }
}
