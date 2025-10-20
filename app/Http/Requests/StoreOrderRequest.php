<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_email' => 'required|email|max:255',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'nullable|string|max:100',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash_on_delivery,bank_transfer',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.variant_name' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1|max:99',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_email.required' => 'Customer email is required',
            'customer_email.email' => 'Please provide a valid email address',
            'customer_first_name.required' => 'Customer first name is required',
            'customer_last_name.required' => 'Customer last name is required',
            'shipping_address.required' => 'Shipping address is required',
            'shipping_city.required' => 'Shipping city is required',
            'subtotal.required' => 'Subtotal is required',
            'subtotal.min' => 'Subtotal must be greater than or equal to 0',
            'total.required' => 'Total is required',
            'total.min' => 'Total must be greater than or equal to 0',
            'items.required' => 'Order items are required',
            'items.min' => 'At least one item is required',
            'items.*.product_id.required' => 'Product ID is required for each item',
            'items.*.product_id.exists' => 'Selected product does not exist',
            'items.*.product_name.required' => 'Product name is required for each item',
            'items.*.quantity.required' => 'Quantity is required for each item',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.quantity.max' => 'Quantity cannot exceed 99',
            'items.*.unit_price.required' => 'Unit price is required for each item',
            'items.*.unit_price.min' => 'Unit price must be greater than or equal to 0',
            'items.*.total_price.required' => 'Total price is required for each item',
            'items.*.total_price.min' => 'Total price must be greater than or equal to 0',
        ];
    }
}