<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Example\TranscarpatianFood\Models\Category;
use Example\TranscarpatianFood\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->input('category');
        $sortBy = $request->input('sort', 'popularity'); // Default sort by popularity
        $sortDirection = $request->input('direction', 'desc'); // Default direction descending

        // Start with active products query
        $productsQuery = Product::where('is_active', true);

        // Apply category filter if provided
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Apply sorting
        $allowedSortFields = ['popularity', 'price', 'name'];
        $allowedDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortFields) && in_array($sortDirection, $allowedDirections)) {
            $productsQuery->orderBy($sortBy, $sortDirection);
        } else {
            $productsQuery->orderBy('popularity', 'desc');
        }

        // Get paginated results
        $products = $productsQuery->paginate(12)->withQueryString();

        // Get all categories for the filter
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('products', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection
        ]);
    }

    /**
     * Display a specific product
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
    {
        // Find the product by slug
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment product popularity
        $product->increment('popularity');

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderBy('popularity', 'desc')
            ->take(4)
            ->get();

        return view('product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
