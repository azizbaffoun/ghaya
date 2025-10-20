<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class FirstDeliverySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_delivery_key',
        'delivery_cost',
        'return_cost',
        'store_name',
        'store_phone',
        'store_address',
        'store_city',
        'vat_number',
        'allow_open_package',
        'is_enabled',
    ];

    protected $casts = [
        'delivery_cost' => 'decimal:2',
        'return_cost' => 'decimal:2',
        'allow_open_package' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    /**
     * Encrypt the first delivery key when storing.
     */
    public function setFirstDeliveryKeyAttribute($value)
    {
        $this->attributes['first_delivery_key'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt the first delivery key when retrieving.
     */
    public function getFirstDeliveryKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Get the current settings (singleton pattern).
     */
    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    /**
     * Check if First Delivery is enabled.
     */
    public static function isEnabled()
    {
        return static::getSettings()->is_enabled ?? false;
    }
}
