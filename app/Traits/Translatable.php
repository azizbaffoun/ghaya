<?php

namespace App\Traits;

use App\Models\Language;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Translatable
{
    /**
     * Get the current language
     */
    public function getCurrentLanguage(): ?Language
    {
        $locale = app()->getLocale();
        return Language::where('code', $locale)->where('is_active', true)->first();
    }

    /**
     * Get translation for current locale
     */
    public function getTranslation(?string $locale = null): ?object
    {
        $locale = $locale ?? app()->getLocale();
        
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($locale) {
                $query->where('code', $locale)->where('is_active', true);
            })
            ->first();

        // Fallback to default language if no translation found
        if (!$translation) {
            $defaultLanguage = Language::where('is_default', true)->where('is_active', true)->first();
            if ($defaultLanguage) {
                $translation = $this->translations()
                    ->where('language_id', $defaultLanguage->id)
                    ->first();
            }
        }

        return $translation;
    }

    /**
     * Get translated attribute
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale = null): ?string
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->{$attribute} : null;
    }

    /**
     * Get translated name
     */
    public function getTranslatedNameAttribute(): ?string
    {
        return $this->getTranslatedAttribute('name');
    }

    /**
     * Get translated description
     */
    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->getTranslatedAttribute('description');
    }

    /**
     * Get translated slug
     */
    public function getTranslatedSlugAttribute(): ?string
    {
        return $this->getTranslatedAttribute('slug');
    }

    /**
     * Scope to get models with translations for current locale
     */
    public function scopeWithTranslation($query, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return $query->with(['translations' => function ($query) use ($locale) {
            $query->whereHas('language', function ($q) use ($locale) {
                $q->where('code', $locale)->where('is_active', true);
            });
        }]);
    }

    /**
     * Scope to get models with default translation
     */
    public function scopeWithDefaultTranslation($query)
    {
        return $query->with(['translations' => function ($query) {
            $query->whereHas('language', function ($q) {
                $q->where('is_default', true)->where('is_active', true);
            });
        }]);
    }

    /**
     * Create or update translation
     */
    public function setTranslation(string $locale, array $data): void
    {
        $language = Language::where('code', $locale)->where('is_active', true)->first();
        
        if (!$language) {
            throw new \Exception("Language with code '{$locale}' not found or inactive");
        }

        $this->translations()->updateOrCreate(
            ['language_id' => $language->id],
            $data
        );
    }

    /**
     * Get all translations
     */
    public function getAllTranslations(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->translations()->with('language')->get();
    }

    /**
     * Check if translation exists for locale
     */
    public function hasTranslation(string $locale): bool
    {
        $language = Language::where('code', $locale)->where('is_active', true)->first();
        
        if (!$language) {
            return false;
        }

        return $this->translations()->where('language_id', $language->id)->exists();
    }
}





