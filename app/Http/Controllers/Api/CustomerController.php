<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Get customer profile
     * GET /api/v1/customer/profile
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            return response()->json([
                'success' => true,
                'data' => new CustomerResource($customer)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update customer profile
     * PUT /api/v1/customer/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => ['sometimes', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
                'phone' => 'sometimes|nullable|string|max:20',
                'address' => 'sometimes|nullable|string|max:500',
                'city' => 'sometimes|nullable|string|max:100',
                'state' => 'sometimes|nullable|string|max:100',
                'postal_code' => 'sometimes|nullable|string|max:20',
                'country' => 'sometimes|nullable|string|max:100',
            ]);

            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => new CustomerResource($customer)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer order history
     * GET /api/v1/customer/orders
     */
    public function orders(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();
            
            $query = $customer->orders()->with(['items.product']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $orders = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => OrderResource::collection($orders->items()),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer addresses (saved addresses)
     * GET /api/v1/customer/addresses
     */
    public function addresses(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            // For now, we'll return the customer's main address
            // In a real app, you might have a separate addresses table
            $addresses = [
                [
                    'id' => 1,
                    'type' => 'main',
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'address' => $customer->address,
                    'city' => $customer->city,
                    'state' => $customer->state,
                    'postal_code' => $customer->postal_code,
                    'country' => $customer->country,
                    'phone' => $customer->phone,
                    'is_default' => true,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $addresses
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get addresses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new address
     * POST /api/v1/customer/addresses
     */
    public function addAddress(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
            ]);

            // Update customer's main address
            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => new CustomerResource($customer)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



