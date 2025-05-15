<?php

namespace Example\TranscarpatianFood\Http\Controllers\Admin;

use Example\TranscarpatianFood\Http\Controllers\Controller;
use Example\TranscarpatianFood\Models\Product;
use Example\TranscarpatianFood\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Показати список всіх товарів
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Показати форму для створення нового товару
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Зберегти новий товар
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Генерувати slug з назви
        $validated['slug'] = Str::slug($validated['name']);

        // Обробка thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        // Обробка додаткових зображень
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $validated['images'] = $images;
        }

        // Встановити значення за замовчуванням
        $validated['popularity'] = 0; // За замовчуванням популярність 0

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успішно створено.');
    }

    /**
     * Показати форму для редагування товару
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Оновити товар
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Генерувати slug з назви
        $validated['slug'] = Str::slug($validated['name']);

        // Обробка thumbnail
        if ($request->hasFile('thumbnail')) {
            // Видалити старий thumbnail, якщо він існує
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        // Обробка додаткових зображень
        $images = $product->images ?? [];

        // Видалити вибрані зображення
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($images[$index])) {
                    Storage::disk('public')->delete($images[$index]);
                    unset($images[$index]);
                }
            }
            $images = array_values($images); // Переіндексувати масив
        }

        // Додати нові зображення
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
        }

        $validated['images'] = $images;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успішно оновлено.');
    }

    /**
     * Видалити товар
     */
    public function destroy(Product $product)
    {
        // Видалити зображення
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успішно видалено.');
    }
}
