<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sizes' => 'sometimes|nullable|array',
            'sizes.*' => 'string|max:50',
            'colors' => 'sometimes|nullable|array',
            'colors.*' => 'string|max:50',
            'stock_status' => 'sometimes|in:in_stock,out_of_stock,pre_order',
            'category_id' => 'sometimes|nullable|integer|exists:categories,id',
        ];
    }
}
