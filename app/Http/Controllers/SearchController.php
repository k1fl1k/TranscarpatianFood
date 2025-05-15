<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Example\TranscarpatianFood\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for products
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('home');
        }

        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->orderBy('popularity', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('search', [
            'products' => $products,
            'query' => $query
        ]);
    }
}
