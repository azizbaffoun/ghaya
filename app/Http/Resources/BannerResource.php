<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translation = $this->translations->first();
        $locale = app()->getLocale();

        // Try to get translation for current locale
        $currentTranslation = $this->translations->where('language.code', $locale)->first();
        if (!$currentTranslation) {
            $currentTranslation = $this->translations->first();
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'settings' => $this->settings,
            'start_date' => $this->start_date?->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date?->format('Y-m-d H:i:s'),
            'translation' => $currentTranslation ? [
                'title' => $currentTranslation->title,
                'subtitle' => $currentTranslation->subtitle,
                'description' => $currentTranslation->description,
                'button_text' => $currentTranslation->button_text,
                'button_url' => $currentTranslation->button_url,
                'image' => $currentTranslation->image ? asset('storage/' . $currentTranslation->image) : null,
                'mobile_image' => $currentTranslation->mobile_image ? asset('storage/' . $currentTranslation->mobile_image) : null,
                'video' => $currentTranslation->video ? asset('storage/' . $currentTranslation->video) : null,
                'video_url' => $currentTranslation->video_url,
            ] : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}



