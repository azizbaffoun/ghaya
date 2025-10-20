<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'path',
        'alt_text',
        'category',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the asset's URL.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get image dimensions from metadata.
     */
    public function getDimensionsAttribute(): ?array
    {
        return $this->metadata['dimensions'] ?? null;
    }

    /**
     * Get file size from metadata.
     */
    public function getFileSizeAttribute(): ?int
    {
        return $this->metadata['size'] ?? null;
    }

    /**
     * Get mime type from metadata.
     */
    public function getMimeTypeAttribute(): ?string
    {
        return $this->metadata['mime_type'] ?? null;
    }
}
