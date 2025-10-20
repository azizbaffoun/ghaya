<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 categories
        $categories = $this->createCategories();
        
        // Create 3 products with variants
        $products = $this->createProducts($categories);
        
        // Create customers
        $customers = $this->createCustomers();
        
        // Create 7 orders with different statuses
        $this->createOrders($customers, $products);
    }

    private function createCategories()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest electronic devices and gadgets',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy clothing and accessories',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Everything for your home and garden',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $createdCategories[] = Category::create($categoryData);
        }

        return $createdCategories;
    }

    private function createProducts($categories)
    {
        $products = [
            [
                'name' => 'Smartphone Pro Max',
                'slug' => 'smartphone-pro-max',
                'description' => 'Latest smartphone with advanced features, high-resolution camera, and long-lasting battery.',
                'short_description' => 'Advanced smartphone with premium features',
                'price' => 899.99,
                'compare_price' => 1099.99,
                'sku' => 'SPM-001',
                'category_id' => $categories[0]->id,
                'is_active' => true,
                'is_featured' => true,
                'weight' => 0.2,
                'sizes' => ['128GB', '256GB', '512GB'],
                'colors' => ['Black', 'White', 'Blue'],
                'stock_status' => 'in_stock',
            ],
            [
                'name' => 'Designer T-Shirt',
                'slug' => 'designer-t-shirt',
                'description' => 'Premium cotton t-shirt with modern design and comfortable fit.',
                'short_description' => 'Premium cotton t-shirt',
                'price' => 29.99,
                'compare_price' => 39.99,
                'sku' => 'DTS-001',
                'category_id' => $categories[1]->id,
                'is_active' => true,
                'is_featured' => false,
                'weight' => 0.15,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Black', 'White', 'Red', 'Navy'],
                'stock_status' => 'in_stock',
            ],
            [
                'name' => 'Smart Home Speaker',
                'slug' => 'smart-home-speaker',
                'description' => 'Voice-controlled smart speaker with excellent sound quality and smart home integration.',
                'short_description' => 'Voice-controlled smart speaker',
                'price' => 149.99,
                'compare_price' => 199.99,
                'sku' => 'SHS-001',
                'category_id' => $categories[2]->id,
                'is_active' => true,
                'is_featured' => true,
                'weight' => 0.8,
                'sizes' => ['Standard'],
                'colors' => ['Black', 'White', 'Gray'],
                'stock_status' => 'in_stock',
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $product = Product::create($productData);
            $createdProducts[] = $product;

            // Create product variants
            $this->createProductVariants($product);
            
            // Create product images
            $this->createProductImages($product);
        }

        return $createdProducts;
    }

    private function createProductVariants($product)
    {
        $variants = [];
        
        // Create variants based on sizes and colors
        foreach ($product->sizes as $size) {
            foreach ($product->colors as $color) {
                $variants[] = [
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $color,
                    'sku' => $product->sku . '-' . strtoupper(substr($size, 0, 2)) . '-' . strtoupper(substr($color, 0, 2)) . '-' . uniqid(),
                    'price' => $product->price,
                    'stock_quantity' => rand(10, 100),
                    'is_active' => true,
                ];
            }
        }

        foreach ($variants as $variantData) {
            ProductVariant::create($variantData);
        }
    }

    private function createProductImages($product)
    {
        $images = [
            [
                'product_id' => $product->id,
                'image_path' => 'products/' . $product->slug . '-1.jpg',
                'alt_text' => $product->name . ' - Main Image',
                'is_primary' => true,
                'sort_order' => 1,
            ],
            [
                'product_id' => $product->id,
                'image_path' => 'products/' . $product->slug . '-2.jpg',
                'alt_text' => $product->name . ' - Side View',
                'is_primary' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($images as $imageData) {
            ProductImage::create($imageData);
        }
    }

    private function createCustomers()
    {
        $customers = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Alami',
                'email' => 'ahmed.alami@email.com',
                'phone' => '+212612345678',
                'address' => '123 Avenue Mohammed V',
                'city' => 'Casablanca',
                'state' => 'Casablanca-Settat',
                'postal_code' => '20000',
                'country' => 'Morocco',
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'Benali',
                'email' => 'fatima.benali@email.com',
                'phone' => '+212612345679',
                'address' => '456 Rue Hassan II',
                'city' => 'Rabat',
                'state' => 'Rabat-Salé-Kénitra',
                'postal_code' => '10000',
                'country' => 'Morocco',
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Tazi',
                'email' => 'omar.tazi@email.com',
                'phone' => '+212612345680',
                'address' => '789 Boulevard Zerktouni',
                'city' => 'Marrakech',
                'state' => 'Marrakech-Safi',
                'postal_code' => '40000',
                'country' => 'Morocco',
            ],
            [
                'first_name' => 'Aicha',
                'last_name' => 'Idrissi',
                'email' => 'aicha.idrissi@email.com',
                'phone' => '+212612345681',
                'address' => '321 Rue de la Liberté',
                'city' => 'Fes',
                'state' => 'Fès-Meknès',
                'postal_code' => '30000',
                'country' => 'Morocco',
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $createdCustomers[] = Customer::create($customerData);
        }

        return $createdCustomers;
    }

    private function createOrders($customers, $products)
    {
        $orderStatuses = [
            'pending', 'processing', 'shipped', 'delivered', 'cancelled'
        ];

        $confirmationStatuses = [
            'pending_confirmation', 'confirmed', 'printed', 
            'pickup_requested', 'in_delivery', 'delivered', 'cancelled'
        ];

        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $paymentMethods = ['cash_on_delivery', 'bank_transfer'];

        $orders = [
            [
                'customer_id' => $customers[0]->id,
                'customer_email' => $customers[0]->email,
                'customer_first_name' => $customers[0]->first_name,
                'customer_last_name' => $customers[0]->last_name,
                'customer_phone' => $customers[0]->phone,
                'shipping_address' => $customers[0]->address,
                'shipping_city' => $customers[0]->city,
                'shipping_state' => $customers[0]->state,
                'shipping_postal_code' => $customers[0]->postal_code,
                'shipping_country' => $customers[0]->country,
                'status' => 'pending',
                'confirmation_status' => 'pending_confirmation',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'subtotal' => 899.99,
                'shipping_cost' => 25.00,
                'total' => 924.99,
                'notes' => 'Please deliver during business hours',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'customer_id' => $customers[1]->id,
                'customer_email' => $customers[1]->email,
                'customer_first_name' => $customers[1]->first_name,
                'customer_last_name' => $customers[1]->last_name,
                'customer_phone' => $customers[1]->phone,
                'shipping_address' => $customers[1]->address,
                'shipping_city' => $customers[1]->city,
                'shipping_state' => $customers[1]->state,
                'shipping_postal_code' => $customers[1]->postal_code,
                'shipping_country' => $customers[1]->country,
                'status' => 'processing',
                'confirmation_status' => 'confirmed',
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'subtotal' => 59.98,
                'shipping_cost' => 15.00,
                'total' => 74.98,
                'notes' => 'Gift wrapping requested',
                'print_url' => 'https://firstdelivery.ma/print/FD' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'confirmed_at' => Carbon::now()->subHours(6),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'customer_id' => $customers[2]->id,
                'customer_email' => $customers[2]->email,
                'customer_first_name' => $customers[2]->first_name,
                'customer_last_name' => $customers[2]->last_name,
                'customer_phone' => $customers[2]->phone,
                'shipping_address' => $customers[2]->address,
                'shipping_city' => $customers[2]->city,
                'shipping_state' => $customers[2]->state,
                'shipping_postal_code' => $customers[2]->postal_code,
                'shipping_country' => $customers[2]->country,
                'status' => 'shipped',
                'confirmation_status' => 'in_delivery',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'subtotal' => 149.99,
                'shipping_cost' => 20.00,
                'total' => 169.99,
                'notes' => 'Fragile - handle with care',
                'barcode' => 'FD' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'shipped_at' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'customer_id' => $customers[3]->id,
                'customer_email' => $customers[3]->email,
                'customer_first_name' => $customers[3]->first_name,
                'customer_last_name' => $customers[3]->last_name,
                'customer_phone' => $customers[3]->phone,
                'shipping_address' => $customers[3]->address,
                'shipping_city' => $customers[3]->city,
                'shipping_state' => $customers[3]->state,
                'shipping_postal_code' => $customers[3]->postal_code,
                'shipping_country' => $customers[3]->country,
                'status' => 'delivered',
                'confirmation_status' => 'delivered',
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'subtotal' => 119.97,
                'shipping_cost' => 18.00,
                'total' => 137.97,
                'notes' => 'Customer satisfied with delivery',
                'shipped_at' => Carbon::now()->subDays(2),
                'delivered_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'customer_id' => $customers[0]->id,
                'customer_email' => $customers[0]->email,
                'customer_first_name' => $customers[0]->first_name,
                'customer_last_name' => $customers[0]->last_name,
                'customer_phone' => $customers[0]->phone,
                'shipping_address' => $customers[0]->address,
                'shipping_city' => $customers[0]->city,
                'shipping_state' => $customers[0]->state,
                'shipping_postal_code' => $customers[0]->postal_code,
                'shipping_country' => $customers[0]->country,
                'status' => 'cancelled',
                'confirmation_status' => 'cancelled',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'refunded',
                'subtotal' => 299.98,
                'shipping_cost' => 25.00,
                'total' => 324.98,
                'notes' => 'Customer requested cancellation',
                'staff_notes' => 'Refund processed successfully',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'customer_id' => $customers[1]->id,
                'customer_email' => $customers[1]->email,
                'customer_first_name' => $customers[1]->first_name,
                'customer_last_name' => $customers[1]->last_name,
                'customer_phone' => $customers[1]->phone,
                'shipping_address' => $customers[1]->address,
                'shipping_city' => $customers[1]->city,
                'shipping_state' => $customers[1]->state,
                'shipping_postal_code' => $customers[1]->postal_code,
                'shipping_country' => $customers[1]->country,
                'status' => 'processing',
                'confirmation_status' => 'printed',
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'subtotal' => 449.97,
                'shipping_cost' => 30.00,
                'total' => 479.97,
                'notes' => 'Priority shipping requested',
                'print_url' => 'https://firstdelivery.ma/print/FD' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'barcode' => 'FD' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'printed_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'customer_id' => $customers[2]->id,
                'customer_email' => $customers[2]->email,
                'customer_first_name' => $customers[2]->first_name,
                'customer_last_name' => $customers[2]->last_name,
                'customer_phone' => $customers[2]->phone,
                'shipping_address' => $customers[2]->address,
                'shipping_city' => $customers[2]->city,
                'shipping_state' => $customers[2]->state,
                'shipping_postal_code' => $customers[2]->postal_code,
                'shipping_country' => $customers[2]->country,
                'status' => 'processing',
                'confirmation_status' => 'pickup_requested',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'subtotal' => 89.97,
                'shipping_cost' => 15.00,
                'total' => 104.97,
                'notes' => 'Customer will pickup from store',
                'barcode' => 'FD' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                'pickup_requested_at' => Carbon::now()->subHours(1),
                'created_at' => Carbon::now()->subHours(4),
            ],
        ];

        foreach ($orders as $orderData) {
            // Generate order number first
            $orderData['order_number'] = 'ORD-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            $order = Order::create($orderData);

            // Create order items
            $this->createOrderItems($order, $products);
        }
    }

    private function createOrderItems($order, $products)
    {
        $orderItems = [];
        
        // Randomly select 1-3 products for each order
        $selectedProducts = collect($products)->random(rand(1, 3));
        
        foreach ($selectedProducts as $product) {
            $variants = $product->variants;
            $variant = $variants->random();
            $quantity = rand(1, 3);
            
            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'product_name' => $product->name,
                'product_sku' => $variant->sku,
                'variant_name' => $variant->size . ' - ' . $variant->color,
                'quantity' => $quantity,
                'unit_price' => $variant->price,
                'total_price' => $variant->price * $quantity,
            ];
        }

        foreach ($orderItems as $itemData) {
            OrderItem::create($itemData);
        }
    }
}
