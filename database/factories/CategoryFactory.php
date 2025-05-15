<?php

namespace Database\Factories;

use Example\TranscarpatianFood\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    protected static array $categoryNames = [
        'Карпатські чаї',
        'Сушені гриби',
        'Травʼяні настоянки',
        'Фрукти та ягоди',
        'Горіхи',
        'Спеції',
        'Соки',
        'Варення',
        'Молочні продукти',
        'Солодощі',
        'Сувеніри',
        'Подарункові набори',
        'Алкогольні настоянки',
        'Соління',
    ];

    public function definition(): array
    {
        // Instead of using fake()->unique() which can cause issues when running out of values,
        // we'll generate a random name and make the slug unique by appending a random string if needed
        $name = fake()->randomElement(self::$categoryNames);
        $baseSlug = Str::slug($name);
        $slug = $baseSlug . '-' . Str::lower(Str::random(5)); // Always add a random suffix

        return [
            'name' => $name,
            'slug' => $slug,
            'image' => "categories/{$baseSlug}.jpg",
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(10, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

