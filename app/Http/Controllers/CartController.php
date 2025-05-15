<?php

namespace Example\TranscarpatianFood\Http\Controllers;

use Example\TranscarpatianFood\Models\Cart;
use Example\TranscarpatianFood\Models\CartItem;
use Example\TranscarpatianFood\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart contents
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        return view('cart', [
            'cart' => $cart,
            'items' => $cart->items()->with('product')->get()
        ]);
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);

        // Check if product already in cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            // Update quantity if product already in cart
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            // Add new item to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Товар додано до кошика');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->item_id);
        $cart = $this->getOrCreateCart();

        // Ensure the item belongs to the current user's cart
        if ($item->cart_id !== $cart->id) {
            return redirect()->back()->with('error', 'Неможливо оновити цей товар');
        }

        $item->quantity = $request->quantity;
        $item->save();

        return redirect()->back()->with('success', 'Кошик оновлено');
    }

    /**
     * Remove an item from the cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        $item = CartItem::findOrFail($request->item_id);
        $cart = $this->getOrCreateCart();

        // Ensure the item belongs to the current user's cart
        if ($item->cart_id !== $cart->id) {
            return redirect()->back()->with('error', 'Неможливо видалити цей товар');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Товар видалено з кошика');
    }

    /**
     * Clear all items from the cart
     */
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return redirect()->back()->with('success', 'Кошик очищено');
    }

    /**
     * Get the current cart or create a new one
     */
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            // For logged in users, get cart by user ID
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id()
            ]);
        } else {
            // For guests, get cart by session ID
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId
            ]);
        }

        return $cart;
    }
}
