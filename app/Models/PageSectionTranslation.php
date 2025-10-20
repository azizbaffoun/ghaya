<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSectionTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_section_id',
        'language_id',
        'title',
        'subtitle',
        'content',
        'button_text',
        'button_url',
        'image',
        'additional_data',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    /**
     * Get the page section that owns the translation
     */
    public function pageSection(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
    }

    /**
     * Get the language that owns the translation
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}





