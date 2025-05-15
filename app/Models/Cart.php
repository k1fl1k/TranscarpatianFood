<?php

namespace Example\TranscarpatianFood\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the total price of all items in the cart.
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Check if the cart is empty.
     */
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * Convert cart to order.
     */
    public function convertToOrder(array $orderData)
    {
        // Create new order
        $order = Order::create([
            'user_id' => $this->user_id,
            'order_number' => 'ORD-' . strtoupper(substr($this->id, 0, 8)),
            'status' => 'pending',
            'total_amount' => $this->total_price,
            'customer_name' => $orderData['name'],
            'customer_email' => $orderData['email'],
            'customer_phone' => $orderData['phone'],
            'shipping_address' => $orderData['address'],
            'notes' => $orderData['notes'] ?? null,
            'payment_method' => $orderData['payment_method'] ?? 'cash',
        ]);

        // Create order items from cart items
        foreach ($this->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Clear the cart
        $this->items()->delete();

        return $order;
    }
}
