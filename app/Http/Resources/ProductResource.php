<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'sizes' => $this->sizes ?? [],
            'colors' => $this->colors ?? [],
            'stock_status' => $this->stock_status ?? 'in_stock',
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->image_path),
                    'image_path' => $image->image_path,
                    'alt_text' => $image->alt_text ?? $this->name,
                    'is_primary' => $image->is_primary ?? false,
                    'color' => $image->color ?? null,
                ];
            }),
            'variants' => $this->whenLoaded('variants', function () {
                return $this->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'size' => $variant->size,
                        'color' => $variant->color,
                        'sku' => $variant->sku,
                        'stock_quantity' => $variant->stock_quantity,
                        'price' => $variant->price,
                        'is_active' => $variant->is_active,
                    ];
                });
            }),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
