<?php

namespace Database\Seeders;

use Example\TranscarpatianFood\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Закарпатські ковбаси',
                'description' => 'Традиційні закарпатські ковбаси, виготовлені за старовинними рецептами.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Сири',
                'description' => 'Натуральні сири з гірських районів Закарпаття.',
                'sort_order' => 20,
            ],
            [
                'name' => 'Вина',
                'description' => 'Колекція найкращих закарпатських вин.',
                'sort_order' => 30,
            ],
            [
                'name' => 'Мед та продукти бджільництва',
                'description' => 'Натуральний мед та інші продукти бджільництва з Карпатських гір.',
                'sort_order' => 40,
            ],
            [
                'name' => 'Консервація',
                'description' => 'Домашня консервація за традиційними закарпатськими рецептами.',
                'sort_order' => 50,
            ],
            [
                'name' => 'Випічка',
                'description' => 'Традиційна закарпатська випічка та десерти.',
                'sort_order' => 60,
            ],
        ];

        // Додаємо категорії з фабрики
        $factoryCategories = [
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

        foreach ($factoryCategories as $index => $name) {
            $categories[] = [
                'name' => $name,
                'description' => fake()->sentence(),
                'sort_order' => 70 + ($index * 10),
            ];
        }

        // Створюємо категорії, перевіряючи чи вони вже існують
        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);

            // Перевіряємо чи категорія вже існує
            if (!Category::where('slug', $slug)->exists()) {
                Category::create([
                    'name' => $category['name'],
                    'slug' => $slug,
                    'description' => $category['description'],
                    'image' => "categories/{$slug}.jpg",
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
