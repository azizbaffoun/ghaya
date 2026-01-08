<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_id',
        'language_id',
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_url',
        'image',
        'mobile_image',
        'video',
        'video_url',
        'additional_images',
    ];

    protected $casts = [
        'additional_images' => 'array',
    ];

    /**
     * Get the banner that owns the translation
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * Get the language that owns the translation
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}





