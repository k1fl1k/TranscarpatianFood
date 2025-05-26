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
        $name = Str::headline(str_replace('-', ' ', $baseName)); // красиво форматоване ім'я
        $price = fake()->randomFloat(2, 100, 500);

        // Опис для кожного продукту
        $descriptions = [
            'kovbasa-beregivska' => 'Традиційна берегівська ковбаса, виготовлена за старовинними рецептами Закарпаття. Має виражений копчений смак і аромат прянощів.',
            'kovbasa-hutsulska' => 'Гуцульська ковбаса з натурального м’яса, приправлена часником, чорним перцем та димом з букових трісок.',
            'syr-selyskyi' => 'Домашній селищний сир, виготовлений з коров’ячого молока. Має ніжну текстуру і легкий молочний смак.',
            'brynza-karpatska' => 'Солона бринза з овечого молока — класичний смак Карпат. Ідеально підходить до баношу чи салатів.',
            'vyno-troyanda' => 'Ароматне закарпатське вино з нотками троянд, витримане у дубових бочках. Має насичений рожевий колір.',
            'vyno-berehivske' => 'Берегівське червоне вино — гордість виноробів регіону. Поєднує в собі легку терпкість та фруктові нотки.',
            'med-karpatskyi' => 'Карпатський мед, зібраний з гірських квітів. Має насичений колір і солодкий квітковий аромат.',
            'hryby-susheni' => 'Сушені білі гриби, зібрані в екологічно чистих лісах Карпат. Ідеально для супів і соусів.',
            'solodoshchi-domashni' => 'Домашні закарпатські солодощі: печиво, горішки з вареним згущеним молоком, медові пряники.',
            'varennia-chornytsia' => 'Чорничне варення, приготоване з дикорослих ягід. Ідеальне до млинців чи просто до чаю.',
            'chay-travianyj' => 'Трав’яний чай з карпатських трав: м’ята, чебрець, материнка та ромашка. Сприяє розслабленню й відновленню сил.',
        ];

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => $slug,
            'description' => $descriptions[$baseName] ?? fake()->paragraphs(3, true),
            'price' => $price,
            'thumbnail' => "products/thumbnails/{$baseName}.jpg",
            'images' => [
                "products/{$baseName}-1.jpg",
                "products/{$baseName}-2.jpg",
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
