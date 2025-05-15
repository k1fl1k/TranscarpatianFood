<?php

namespace Database\Factories;

use Example\TranscarpatianFood\Models\Category;
use Example\TranscarpatianFood\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    protected static array $imageNames = [
        'kovbasa-beregivska',
        'kovbasa-hutsulska',
        'syr-selyskyi',
        'brynza-karpatska',
        'vyno-troyanda',
        'vyno-berehivske',
        'med-karpatskyi',
        'hryby-susheni',
        'solodoshchi-domashni',
        'varennia-chornytsia',
        'chay-travianyj',
    ];

    public function definition(): array
    {
        $baseName = fake()->randomElement(self::$imageNames);
        $randomSuffix = Str::random(5);
        $slug = $baseName . '-' . $randomSuffix;
        $name = Str::headline(str_replace('-', ' ', $baseName)); // для красивого вигляду
        $price = fake()->randomFloat(2, 100, 500);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => $slug, // Now includes a random suffix to ensure uniqueness
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'thumbnail' => "products/thumbnails/{$baseName}.jpg",
            'images' => [
                "products/{$baseName}-1.jpg",
                "products/{$baseName}-2.jpg",
                "products/{$baseName}-3.jpg",
            ],
            'popularity' => fake()->numberBetween(100, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'popularity' => fake()->numberBetween(800, 1000),
        ]);
    }
}
