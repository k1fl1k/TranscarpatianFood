<?php

namespace Example\TranscarpatianFood\Http\Controllers\Admin;

use Example\TranscarpatianFood\Http\Controllers\Controller;
use Example\TranscarpatianFood\Models\Order;
use Example\TranscarpatianFood\Models\Product;
use Example\TranscarpatianFood\Models\User;
use Example\TranscarpatianFood\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Показати панель приладів адміністратора
     */
    public function index()
    {
        // Статистика замовлень
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        // Статистика товарів
        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        
        // Статистика користувачів
        $totalUsers = User::count();
        $newUsers = User::whereDate('created_at', '>=', now()->subDays(30))->count();
        
        // Статистика категорій
        $totalCategories = Category::count();
        
        // Останні замовлення
        $latestOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Популярні товари
        $popularProducts = Product::orderBy('popularity', 'desc')
            ->take(5)
            ->get();
        
        // Статистика замовлень за останні 7 днів
        $orderStats = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders', 
            'pendingOrders', 
            'totalRevenue', 
            'totalProducts', 
            'outOfStockProducts', 
            'totalUsers', 
            'newUsers', 
            'totalCategories', 
            'latestOrders', 
            'popularProducts', 
            'orderStats'
        ));
    }
}
