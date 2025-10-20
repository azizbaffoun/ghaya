<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomepageSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = json_decode($this->data, true) ?? [];
        $settings = json_decode($this->settings, true) ?? [];
        
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'title' => $data['title'] ?? $this->name,
            'content' => $data['content'] ?? '',
            'order' => $this->sort_order,
            'is_active' => $this->is_active,
            'settings' => $settings,
            'data' => $data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
