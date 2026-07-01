<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            "image"=> $this->faker->imageUrl(),
            "title"=> $title,
            "slug"=> \Illuminate\Support\str::slug($title),
            "content"=> $this->faker->paragraph(5),
            'category_id'=> Category::inRandomOrder()->first()->id,
            'user_id'=> 1,
            'published_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
