<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->words(3, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(3),
            'tech_stack' => fake()->words(5, true),
            'image' => null,
            'link' => fake()->url(),
            'featured' => fake()->boolean(70),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    public function notFeatured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => false,
        ]);
    }
}
