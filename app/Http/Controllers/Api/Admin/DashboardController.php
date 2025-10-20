<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     * GET /api/v1/admin/dashboard/stats
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', '30'); // days
            $startDate = now()->subDays($period);

            $stats = [
                'orders' => [
                    'total' => Order::count(),
                    'pending' => Order::where('status', 'pending')->count(),
                    'confirmed' => Order::where('confirmation_status', 'confirmed')->count(),
                    'delivered' => Order::where('status', 'delivered')->count(),
                    'cancelled' => Order::where('status', 'cancelled')->count(),
                    'recent' => Order::where('created_at', '>=', $startDate)->count(),
                ],
                'products' => [
                    'total' => Product::count(),
                    'active' => Product::where('is_active', true)->count(),
                    'featured' => Product::where('is_featured', true)->count(),
                    'in_stock' => Product::where('stock_status', 'in_stock')->count(),
                    'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
                ],
                'customers' => [
                    'total' => Customer::count(),
                    'recent' => Customer::where('created_at', '>=', $startDate)->count(),
                ],
                'categories' => [
                    'total' => Category::count(),
                    'active' => Category::where('is_active', true)->count(),
                ],
                'revenue' => [
                    'total' => Order::where('status', 'delivered')->sum('subtotal'),
                    'recent' => Order::where('status', 'delivered')
                        ->where('created_at', '>=', $startDate)
                        ->sum('subtotal'),
                    'average_order_value' => Order::where('status', 'delivered')->avg('subtotal'),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent orders
     * GET /api/v1/admin/dashboard/recent-orders
     */
    public function recentOrders(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            
            $orders = Order::with(['items.product'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            $formattedOrders = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_first_name . ' ' . $order->customer_last_name,
                    'customer_email' => $order->customer_email,
                    'total' => $order->total,
                    'status' => $order->status,
                    'confirmation_status' => $order->confirmation_status,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'items_count' => $order->items->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedOrders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get recent orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get best selling products
     * GET /api/v1/admin/dashboard/top-products
     */
    public function topProducts(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $period = $request->get('period', '30');
            $startDate = now()->subDays($period);

            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', $startDate)
                ->where('orders.status', '!=', 'cancelled')
                ->select(
                    'products.id',
                    'products.name',
                    'products.sku',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.total_price) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderBy('total_sold', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get top products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue statistics
     * GET /api/v1/admin/dashboard/revenue
     */
    public function revenue(Request $request): JsonResponse
    {
        try {
            $period = $request->get('period', '30');
            $startDate = now()->subDays($period);

            $revenue = [
                'total' => Order::where('status', 'delivered')->sum('subtotal'),
                'recent' => Order::where('status', 'delivered')
                    ->where('created_at', '>=', $startDate)
                    ->sum('subtotal'),
                'average_order_value' => Order::where('status', 'delivered')->avg('subtotal'),
                'orders_count' => Order::where('status', 'delivered')->count(),
                'recent_orders_count' => Order::where('status', 'delivered')
                    ->where('created_at', '>=', $startDate)
                    ->count(),
            ];

            // Daily revenue for the last 7 days
            $dailyRevenue = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dailyTotal = Order::where('status', 'delivered')
                    ->whereDate('created_at', $date)
                    ->sum('subtotal');
                
                $dailyRevenue[] = [
                    'date' => $date,
                    'revenue' => $dailyTotal
                ];
            }

            $revenue['daily'] = $dailyRevenue;

            return response()->json([
                'success' => true,
                'data' => $revenue
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get revenue statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



