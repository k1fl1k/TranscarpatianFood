<?php

namespace Example\TranscarpatianFood\Http\Controllers\Admin;

use Example\TranscarpatianFood\Http\Controllers\Controller;
use Example\TranscarpatianFood\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Показати список всіх замовлень
     */
    public function index()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Показати деталі замовлення
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Оновити статус замовлення
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        $order->status = $validated['status'];
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Статус замовлення успішно оновлено.');
    }
}
