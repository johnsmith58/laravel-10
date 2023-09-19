<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'sub_title' => $this->faker->title(),
            'content' => $this->faker->paragraph(),
            'img_src' => $this->faker->imageUrl(),
            'author_name' => $this->faker->name(),
            'order_no' => rand(100, 999)
        ];
    }
}
