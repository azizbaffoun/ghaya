<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\PageSection;
use App\Models\HomepageAsset;
use App\Models\Order;
use App\Models\OrderItem;

class ApiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCategories();
        $this->seedProducts();
        $this->seedHomepageSections();
        $this->seedHomepageAssets();
        $this->seedOrders();
    }

    private function seedCategories(): void
    {
        // Root categories
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $clothing = Category::firstOrCreate(
            ['slug' => 'clothing'],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $home = Category::firstOrCreate(
            ['slug' => 'home-garden'],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement and garden supplies',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // Sub-categories
        Category::firstOrCreate(
            ['slug' => 'smartphones'],
            [
                'name' => 'Smartphones',
                'description' => 'Mobile phones and accessories',
                'parent_id' => $electronics->id,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'laptops'],
            [
                'name' => 'Laptops',
                'description' => 'Portable computers and accessories',
                'parent_id' => $electronics->id,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'mens-clothing'],
            [
                'name' => 'Men\'s Clothing',
                'description' => 'Clothing for men',
                'parent_id' => $clothing->id,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'womens-clothing'],
            [
                'name' => 'Women\'s Clothing',
                'description' => 'Clothing for women',
                'parent_id' => $clothing->id,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }

    private function seedProducts(): void
    {
        $categories = Category::all();
        
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Latest iPhone with advanced camera system and A17 Pro chip',
                'price' => 999.99,
                'sku' => 'IPH15PRO001',
                'sizes' => ['128GB', '256GB', '512GB', '1TB'],
                'colors' => ['Natural Titanium', 'Blue Titanium', 'White Titanium', 'Black Titanium'],
                'stock_status' => 'in_stock',
                'category_id' => $categories->where('slug', 'smartphones')->first()->id,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'MacBook Pro 16"',
                'slug' => 'macbook-pro-16',
                'description' => 'Powerful laptop with M3 Pro chip and stunning Liquid Retina XDR display',
                'price' => 2499.99,
                'sku' => 'MBP16M3001',
                'sizes' => ['512GB', '1TB', '2TB', '4TB'],
                'colors' => ['Space Gray', 'Silver'],
                'stock_status' => 'in_stock',
                'category_id' => $categories->where('slug', 'laptops')->first()->id,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Classic T-Shirt',
                'slug' => 'classic-t-shirt',
                'description' => 'Comfortable cotton t-shirt in various colors',
                'price' => 29.99,
                'sku' => 'TSHIRT001',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Black', 'White', 'Navy', 'Gray', 'Red'],
                'stock_status' => 'in_stock',
                'category_id' => $categories->where('slug', 'mens-clothing')->first()->id,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Summer Dress',
                'slug' => 'summer-dress',
                'description' => 'Light and breezy summer dress perfect for warm weather',
                'price' => 79.99,
                'sku' => 'DRESS001',
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'colors' => ['Floral', 'Solid Blue', 'Solid Pink', 'Striped'],
                'stock_status' => 'in_stock',
                'category_id' => $categories->where('slug', 'womens-clothing')->first()->id,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Gaming Laptop',
                'slug' => 'gaming-laptop',
                'description' => 'High-performance gaming laptop with RTX graphics',
                'price' => 1599.99,
                'sku' => 'GAMELAPTOP001',
                'sizes' => ['512GB', '1TB', '2TB'],
                'colors' => ['Black', 'RGB'],
                'stock_status' => 'pre_order',
                'category_id' => $categories->where('slug', 'laptops')->first()->id,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }

    private function seedHomepageSections(): void
    {
        $sections = [
            [
                'page' => 'homepage',
                'type' => 'hero',
                'name' => 'Welcome to Our Store',
                'sort_order' => 1,
                'is_active' => true,
                'settings' => [
                    'background_color' => '#f8f9fa',
                    'text_color' => '#333333',
                    'button_text' => 'Shop Now',
                    'button_url' => '/products',
                ],
                'data' => [
                    'title' => 'Welcome to Our Store',
                    'content' => 'Discover amazing products at unbeatable prices',
                ],
            ],
            [
                'page' => 'homepage',
                'type' => 'featured_products',
                'name' => 'Featured Products',
                'sort_order' => 2,
                'is_active' => true,
                'settings' => [
                    'product_count' => 4,
                    'show_prices' => true,
                    'show_ratings' => true,
                ],
                'data' => [
                    'title' => 'Featured Products',
                    'content' => 'Check out our most popular items',
                ],
            ],
            [
                'page' => 'homepage',
                'type' => 'banner',
                'name' => 'Special Offer',
                'sort_order' => 3,
                'is_active' => true,
                'settings' => [
                    'background_image' => 'banner-special-offer.jpg',
                    'text_color' => '#ffffff',
                    'button_text' => 'Shop Electronics',
                    'button_url' => '/categories/electronics',
                ],
                'data' => [
                    'title' => 'Special Offer',
                    'content' => 'Get 20% off on all electronics this week!',
                ],
            ],
            [
                'page' => 'homepage',
                'type' => 'testimonials',
                'name' => 'What Our Customers Say',
                'sort_order' => 4,
                'is_active' => true,
                'settings' => [
                    'testimonial_count' => 3,
                    'show_ratings' => true,
                ],
                'data' => [
                    'title' => 'What Our Customers Say',
                    'content' => 'Read reviews from satisfied customers',
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            // Convert arrays to JSON strings for database storage
            $sectionData['settings'] = json_encode($sectionData['settings']);
            $sectionData['data'] = json_encode($sectionData['data']);
            PageSection::create($sectionData);
        }
    }

    private function seedHomepageAssets(): void
    {
        $assets = [
            [
                'type' => 'image',
                'name' => 'Hero Background',
                'path' => 'homepage-assets/image/2024/01/hero-background.jpg',
                'alt_text' => 'Hero section background image',
                'category' => 'hero',
                'metadata' => [
                    'original_name' => 'hero-bg.jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => 2048000,
                    'extension' => 'jpg',
                    'dimensions' => ['width' => 1920, 'height' => 1080],
                ],
            ],
            [
                'type' => 'image',
                'name' => 'Special Offer Banner',
                'path' => 'homepage-assets/image/2024/01/special-offer-banner.jpg',
                'alt_text' => 'Special offer banner',
                'category' => 'banner',
                'metadata' => [
                    'original_name' => 'special-offer.jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => 1536000,
                    'extension' => 'jpg',
                    'dimensions' => ['width' => 1200, 'height' => 400],
                ],
            ],
            [
                'type' => 'icon',
                'name' => 'Free Shipping Icon',
                'path' => 'homepage-assets/icon/2024/01/free-shipping.svg',
                'alt_text' => 'Free shipping icon',
                'category' => 'icon',
                'metadata' => [
                    'original_name' => 'free-shipping.svg',
                    'mime_type' => 'image/svg+xml',
                    'size' => 2048,
                    'extension' => 'svg',
                ],
            ],
        ];

        foreach ($assets as $assetData) {
            HomepageAsset::create($assetData);
        }
    }

    private function seedOrders(): void
    {
        $products = Product::take(3)->get();
        
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_email' => 'john.doe@example.com',
            'customer_first_name' => 'John',
            'customer_last_name' => 'Doe',
            'customer_phone' => '+1234567890',
            'shipping_address' => '123 Main St, New York, NY 10001, USA',
            'shipping_city' => 'New York',
            'shipping_state' => 'NY',
            'shipping_postal_code' => '10001',
            'shipping_country' => 'USA',
            'status' => 'pending',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'subtotal' => 0,
            'shipping_cost' => 9.99,
            'total' => 0,
        ]);

        $subtotal = 0;
        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $itemTotal = $product->price * $quantity;
            $subtotal += $itemTotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total_price' => $itemTotal,
            ]);
        }

        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $order->shipping_cost,
        ]);
    }
}
