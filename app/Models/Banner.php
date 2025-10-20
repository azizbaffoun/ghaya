<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Banner extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'type',
        'position',
        'is_active',
        'sort_order',
        'settings',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the translations for the banner
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BannerTranslation::class);
    }

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered banners
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for banners by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for banners by position
     */
    public function scopeAtPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope for currently active banners (within date range)
     */
    public function scopeCurrentlyActive($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    /**
     * Get the banner's title in current locale
     */
    public function getTitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('title');
    }

    /**
     * Get the banner's subtitle in current locale
     */
    public function getSubtitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('subtitle');
    }

    /**
     * Get the banner's description in current locale
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->getTranslatedAttribute('description');
    }

    /**
     * Get the banner's button text in current locale
     */
    public function getButtonTextAttribute(): ?string
    {
        return $this->getTranslatedAttribute('button_text');
    }

    /**
     * Get the banner's button URL in current locale
     */
    public function getButtonUrlAttribute(): ?string
    {
        return $this->getTranslatedAttribute('button_url');
    }

    /**
     * Get the banner's image in current locale
     */
    public function getImageAttribute(): ?string
    {
        return $this->getTranslatedAttribute('image');
    }

    /**
     * Get the banner's mobile image in current locale
     */
    public function getMobileImageAttribute(): ?string
    {
        return $this->getTranslatedAttribute('mobile_image');
    }

    /**
     * Check if banner is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }
}





