<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(4, true));

        return [
            'article_category_id' => ArticleCategory::factory(),
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'excerpt' => fake()->sentence(20),
            'content' => "<p>".fake()->paragraph(3)."</p><p>".fake()->paragraph(3)."</p><h3>Tips Perawatan</h3><p>".fake()->paragraph(3)."</p>",
            'status' => Article::STATUS_PUBLISHED,
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
            'views' => fake()->numberBetween(0, 5000),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => Article::STATUS_DRAFT, 'published_at' => null]);
    }
}