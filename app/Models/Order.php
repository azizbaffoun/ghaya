<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'status',
        'confirmation_status',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
        'staff_notes',
        'shipped_at',
        'delivered_at',
        'first_delivery_id',
        'first_delivery_tracking_number',
        'first_delivery_status',
        'first_delivery_response',
        'print_url',
        'barcode',
        'confirmed_at',
        'printed_at',
        'pickup_requested_at',
        'reminder_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'printed_at' => 'datetime',
        'pickup_requested_at' => 'datetime',
        'reminder_at' => 'datetime',
        'first_delivery_response' => 'array',
        'shipping_address' => 'array',
        'status' => 'string',
        'confirmation_status' => 'string',
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the customer's full name.
     */
    public function getCustomerFullNameAttribute(): string
    {
        return $this->customer_first_name . ' ' . $this->customer_last_name;
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope a query to search orders.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_first_name', 'like', "%{$search}%")
                    ->orWhere('customer_last_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter by confirmation status.
     */
    public function scopeByConfirmationStatus($query, $status)
    {
        return $query->where('confirmation_status', $status);
    }

    /**
     * Scope a query to filter pending confirmation orders.
     */
    public function scopePendingConfirmation($query)
    {
        return $query->where('confirmation_status', 'pending_confirmation');
    }

    /**
     * Scope a query to filter confirmed orders.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmation_status', 'confirmed');
    }

    /**
     * Scope a query to filter printed orders.
     */
    public function scopePrinted($query)
    {
        return $query->where('confirmation_status', 'printed');
    }

    /**
     * Scope a query to filter pickup requested orders.
     */
    public function scopePickupRequested($query)
    {
        return $query->where('confirmation_status', 'pickup_requested');
    }

    /**
     * Scope a query to filter in delivery orders.
     */
    public function scopeInDelivery($query)
    {
        return $query->where('confirmation_status', 'in_delivery');
    }

    /**
     * Get the formatted phone number for display.
     */
    public function getFormattedPhoneAttribute(): string
    {
        if (!$this->customer_phone) {
            return 'N/A';
        }
        
        // Format phone number for display (add spaces, etc.)
        $phone = preg_replace('/[^0-9]/', '', $this->customer_phone);
        if (strlen($phone) >= 8) {
            return substr($phone, 0, 2) . ' ' . substr($phone, 2, 2) . ' ' . substr($phone, 4, 2) . ' ' . substr($phone, 6);
        }
        
        return $this->customer_phone;
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        
        // Get the count of orders created today
        $todayOrdersCount = static::whereDate('created_at', today())->count();
        
        // The next order number for today
        $sequence = $todayOrdersCount + 1;
        
        return $sequence . '-' . $day . '-' . $month . '-' . $year;
    }

    /**
     * Check if order is synced to First Delivery.
     */
    public function isSyncedToFirstDelivery(): bool
    {
        return !is_null($this->first_delivery_id);
    }

    /**
     * Sync order to First Delivery.
     */
    public function syncToFirstDelivery()
    {
        if (!\App\Models\FirstDeliverySetting::isEnabled()) {
            return false;
        }

        $service = app(\App\Services\FirstDeliveryService::class);
        return $service->createOrder($this);
    }

    /**
     * Get available status values.
     */
    public static function getStatusValues(): array
    {
        return ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    }

    /**
     * Get available confirmation status values.
     */
    public static function getConfirmationStatusValues(): array
    {
        return ['pending_confirmation', 'confirmed', 'printed', 'pickup_requested', 'in_delivery', 'delivered', 'cancelled'];
    }

    /**
     * Check if order is ready for pickup (printed or pickup_requested).
     */
    public function isReadyForPickup(): bool
    {
        return in_array($this->confirmation_status, ['printed', 'pickup_requested']);
    }

    /**
     * Check if order can be confirmed.
     */
    public function canBeConfirmed(): bool
    {
        return $this->confirmation_status === 'pending_confirmation';
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return !in_array($this->confirmation_status, ['delivered', 'cancelled']);
    }
}
