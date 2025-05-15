<?php

namespace Example\TranscarpatianFood\Http\Controllers\Admin;

use Example\TranscarpatianFood\Http\Controllers\Controller;
use Example\TranscarpatianFood\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Показати список всіх категорій
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Показати форму для створення нової категорії
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Зберегти нову категорію
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Генерувати slug з назви
        $validated['slug'] = Str::slug($validated['name']);

        // Обробка зображення
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Встановити значення за замовчуванням
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категорію успішно створено.');
    }

    /**
     * Показати форму для редагування категорії
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Оновити категорію
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Генерувати slug з назви
        $validated['slug'] = Str::slug($validated['name']);

        // Обробка зображення
        if ($request->hasFile('image')) {
            // Видалити старе зображення, якщо воно існує
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Встановити значення за замовчуванням
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категорію успішно оновлено.');
    }

    /**
     * Видалити категорію
     */
    public function destroy(Category $category)
    {
        // Перевірити, чи є товари в категорії
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Неможливо видалити категорію, оскільки вона містить товари.');
        }

        // Видалити зображення
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категорію успішно видалено.');
    }
}
