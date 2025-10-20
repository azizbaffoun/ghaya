<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $variant = $this->productVariant;
        $price = $variant ? $variant->price : $product->price;
        $total = $this->quantity * $price;

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'unit_price' => $price,
            'total_price' => $total,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'image' => $product->main_image_url,
                'images' => $product->image_urls,
            ],
            'variant' => $variant ? [
                'id' => $variant->id,
                'color' => $variant->color,
                'size' => $variant->size,
                'sku' => $variant->sku,
                'price' => $variant->price,
            ] : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}



