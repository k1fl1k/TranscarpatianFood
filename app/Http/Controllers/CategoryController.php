<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Example\TranscarpatianFood\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display products in a specific category
     */
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('is_active', true)
            ->orderBy('popularity', 'desc')
            ->paginate(12);

        return view('categories.show', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
