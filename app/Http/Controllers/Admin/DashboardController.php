<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Set language from request
        $locale = $request->get('lang', 'fr');
        App::setLocale($locale);
        
        // Build query for recent orders with filters
        $query = Order::with(['customer', 'orderItems.product', 'orderItems.productVariant']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        // Apply confirmation status filter
        if ($request->filled('confirmation_status')) {
            $query->byConfirmationStatus($request->confirmation_status);
        }
        
        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get recent orders with customer details
        $recentOrders = $query->orderBy('created_at', 'desc')->limit(20)->get();
        
        // Get dashboard metrics
        $metrics = [
            'total_products' => Product::count(),
            'active_orders' => Order::whereIn('status', ['processing', 'shipped'])->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'processing')->sum('subtotal') ?? 0,
            'new_customers' => Customer::whereDate('created_at', today())->count(),
            'low_stock_products' => Product::whereHas('variants', function($query) {
                $query->where('stock_quantity', '<', 10);
            })->count(),
            'ready_for_pickup_orders' => Order::whereIn('confirmation_status', ['printed', 'pickup_requested'])->count(),
            'in_delivery_orders' => Order::where('confirmation_status', 'in_delivery')->count(),
        ];
        
        return view('admin.dashboard', compact('recentOrders', 'metrics', 'locale'));
    }
}
