<?php

namespace Example\TranscarpatianFood\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the total number of items in the order.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the status label for the order.
     */
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Очікує обробки',
            'processing' => 'В обробці',
            'shipped' => 'Відправлено',
            'delivered' => 'Доставлено',
            'cancelled' => 'Скасовано'
        ];
        
        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Get the status color for the order.
     */
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $statusColors[$this->status] ?? 'secondary';
    }

    /**
     * Scope a query to only include orders with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Update order status.
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();

        // Here you could add event dispatching for order status changes
        // event(new OrderStatusChanged($this));

        return $this;
    }
}
