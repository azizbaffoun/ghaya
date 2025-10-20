<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Translatable;

class PageSection extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'page',
        'type',
        'title',
        'content',
        'order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Get the translations for the page section
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PageSectionTranslation::class);
    }

    /**
     * Scope for active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sections
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for sections by page
     */
    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    /**
     * Scope for sections by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the section's title in current locale
     */
    public function getTitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('title');
    }

    /**
     * Get the section's subtitle in current locale
     */
    public function getSubtitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('subtitle');
    }

    /**
     * Get the section's content in current locale
     */
    public function getContentAttribute(): ?string
    {
        return $this->getTranslatedAttribute('content');
    }

    /**
     * Get the section's button text in current locale
     */
    public function getButtonTextAttribute(): ?string
    {
        return $this->getTranslatedAttribute('button_text');
    }

    /**
     * Get the section's button URL in current locale
     */
    public function getButtonUrlAttribute(): ?string
    {
        return $this->getTranslatedAttribute('button_url');
    }

    /**
     * Get the section's image in current locale
     */
    public function getImageAttribute(): ?string
    {
        return $this->getTranslatedAttribute('image');
    }

    /**
     * Get categories for category-grid sections
     */
    public function getCategories()
    {
        if ($this->type !== 'category-grid' || !isset($this->data['category_ids'])) {
            return collect();
        }

        return Category::whereIn('id', $this->data['category_ids'])
            ->active()
            ->ordered()
            ->withTranslation()
            ->get();
    }

    /**
     * Get products for product-grid sections
     */
    public function getProducts()
    {
        if ($this->type !== 'product-grid' || !isset($this->data['product_ids'])) {
            return collect();
        }

        return Product::whereIn('id', $this->data['product_ids'])
            ->active()
            ->withTranslation()
            ->get();
    }

    /**
     * Get banners for banner sections
     */
    public function getBanners()
    {
        if ($this->type !== 'banner' && $this->type !== 'banner-slider') {
            return collect();
        }

        $bannerType = $this->data['banner_type'] ?? 'hero';
        $position = $this->data['position'] ?? null;

        $query = Banner::active()
            ->currentlyActive()
            ->ofType($bannerType)
            ->withTranslation()
            ->ordered();

        if ($position) {
            $query->atPosition($position);
        }

        return $query->get();
    }

    /**
     * Check if section should be displayed
     */
    public function shouldDisplay(): bool
    {
        return $this->is_active;
    }
}


