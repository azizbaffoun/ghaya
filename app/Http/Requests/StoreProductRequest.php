<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:100',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gt:price',
            'stock_status' => 'required|in:in_stock,out_of_stock,pre_order',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'sizes' => 'nullable|array',
            'sizes.*' => 'string|max:20',
            'colors' => 'nullable|array',
            'colors.*' => 'string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'This SKU is already in use',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'price.required' => 'Price is required',
            'price.min' => 'Price must be greater than or equal to 0',
            'compare_price.gt' => 'Compare price must be greater than regular price',
            'stock_status.required' => 'Stock status is required',
            'stock_status.in' => 'Invalid stock status',
            'weight.min' => 'Weight must be greater than or equal to 0',
            'images.max' => 'Maximum 10 images allowed',
            'images.*.image' => 'Each file must be an image',
            'images.*.mimes' => 'Images must be jpeg, png, jpg, or gif',
            'images.*.max' => 'Each image must be less than 10MB',
        ];
    }
}



