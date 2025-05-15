<?php

namespace Database\Seeders;

use Example\TranscarpatianFood\Models\Category;
use Example\TranscarpatianFood\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Отримуємо всі категорії
        $categories = Category::all();

        // Для кожної категорії створюємо кілька продуктів
        foreach ($categories as $category) {
            $this->seedProductsForCategory($category);
        }

        // Додаємо ще кілька випадкових продуктів
        $randomCategories = Category::inRandomOrder()->take(5)->get();
        foreach ($randomCategories as $category) {
            Product::factory()
                ->count(4)
                ->create(['category_id' => $category->id]);
        }

        // Створюємо кілька популярних продуктів
        $popularCategories = Category::inRandomOrder()->take(3)->get();
        foreach ($popularCategories as $category) {
            Product::factory()
                ->count(3)
                ->popular()
                ->create(['category_id' => $category->id]);
        }

        // Створюємо кілька продуктів, яких немає в наявності
        $outOfStockCategories = Category::inRandomOrder()->take(2)->get();
        foreach ($outOfStockCategories as $category) {
            Product::factory()
                ->count(2)
                ->outOfStock()
                ->create(['category_id' => $category->id]);
        }
    }

    /**
     * Seed products for a specific category.
     */
    private function seedProductsForCategory(Category $category): void
    {
        $products = [];

        if ($category->slug === 'zakarpatski-kovbasy') {
            $products = [
                [
                    'name' => 'Ковбаса "Берегівська"',
                    'price' => 320.00,
                    'description' => 'Традиційна копчена ковбаса за рецептом з Берегівського району. Виготовлена з відбірної свинини з додаванням спецій.',
                ],
                [
                    'name' => 'Ковбаса "Гуцульська"',
                    'price' => 350.00,
                    'description' => 'Гостра копчена ковбаса за старовинним гуцульським рецептом. Містить свинину, яловичину та традиційні карпатські спеції.',
                ],
            ];
        } elseif ($category->slug === 'syry') {
            $products = [
                [
                    'name' => 'Сир "Селиський"',
                    'price' => 180.00,
                    'description' => 'М\'який сир, виготовлений з коров\'ячого молока за традиційною технологією в селі Селисько.',
                ],
                [
                    'name' => 'Бринза "Карпатська"',
                    'price' => 210.00,
                    'description' => 'Традиційна карпатська бринза з овечого молока. Має солонуватий смак і м\'яку консистенцію.',
                ],
            ];
        } elseif ($category->slug === 'vyna') {
            $products = [
                [
                    'name' => 'Вино "Троянда Закарпаття"',
                    'price' => 250.00,
                    'description' => 'Напівсолодке рожеве вино з винограду сорту Троянда Закарпаття. Має фруктовий аромат і м\'який смак.',
                ],
                [
                    'name' => 'Вино "Берегівське"',
                    'price' => 280.00,
                    'description' => 'Сухе червоне вино з винограду сорту Каберне Совіньйон. Вирощено в Берегівському районі.',
                ],
            ];
        }

        foreach ($products as $productData) {
            $slug = Str::slug($productData['name']);

            // Перевіряємо чи продукт вже існує
            if (!Product::where('slug', $slug)->exists()) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => $slug,
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'thumbnail' => 'products/thumbnails/' . $slug . '.jpg',
                    'images' => [
                        'products/' . $slug . '-1.jpg',
                        'products/' . $slug . '-2.jpg',
                    ],
                    'popularity' => rand(100, 500),
                    'stock' => rand(10, 50),
                    'is_active' => true,
                ]);
            }
        }
    }
}
