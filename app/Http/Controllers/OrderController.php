<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Example\TranscarpatianFood\Models\Order;
use Example\TranscarpatianFood\Models\OrderItem;
use Example\TranscarpatianFood\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Показати сторінку оформлення замовлення
     */
    public function checkout()
    {
        // Отримати кошик користувача
        $cart = $this->getCart();

        // Якщо кошик порожній, перенаправити на сторінку кошика
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваш кошик порожній. Додайте товари перед оформленням замовлення.');
        }

        // Отримати товари в кошику
        $items = $cart->items()->with('product')->get();

        // Отримати дані користувача, якщо він авторизований
        $user = Auth::user();

        return view('checkout', [
            'cart' => $cart,
            'items' => $items,
            'user' => $user
        ]);
    }

    /**
     * Обробити замовлення
     */
    public function process(Request $request)
    {
        // Валідація даних замовлення
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,card',
            'notes' => 'nullable|string|max:500',
        ]);

        // Отримати кошик
        $cart = $this->getCart();

        // Якщо кошик порожній, перенаправити на сторінку кошика
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваш кошик порожній. Додайте товари перед оформленням замовлення.');
        }

        // Створити нове замовлення
        $order = new Order();
        $order->user_id = Auth::id(); // може бути null для гостя
        $order->order_number = 'ORD-' . strtoupper(Str::random(8));
        $order->status = 'pending';
        $order->total_amount = $cart->getTotalPriceAttribute();
        $order->customer_name = $validated['name'];
        $order->customer_email = $validated['email'];
        $order->customer_phone = $validated['phone'];
        $order->shipping_address = $validated['address'] . ', ' . $validated['city'] . ', ' . $validated['postal_code'];
        $order->notes = $validated['notes'] ?? null;
        $order->payment_method = $validated['payment_method'];
        $order->save();

        // Створити елементи замовлення з елементів кошика
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Очистити кошик
        $cart->items()->delete();

        // Перенаправити на сторінку підтвердження замовлення
        return redirect()->route('order.confirmation', ['order' => $order->id])->with('success', 'Ваше замовлення успішно оформлено!');
    }

    /**
     * Показати сторінку підтвердження замовлення
     */
    public function confirmation(Order $order)
    {
        // Перевірити, чи належить замовлення поточному користувачу (якщо він авторизований)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403, 'Ви не маєте доступу до цього замовлення');
        }

        // Отримати елементи замовлення
        $items = $order->items;

        return view('order-confirmation', [
            'order' => $order,
            'items' => $items
        ]);
    }

    /**
     * Отримати кошик поточного користувача
     */
    private function getCart()
    {
        if (Auth::check()) {
            // Для авторизованих користувачів отримуємо кошик за ID користувача
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        } else {
            // Для гостей отримуємо кошик за ID сесії
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId
            ]);
        }

        return $cart;
    }
}