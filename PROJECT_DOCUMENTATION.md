# Yakine Mode - Ecommerce Project Documentation

## Project Overview
**Yakine Mode** is a clothing ecommerce website built with Laravel 10, featuring a streamlined guest checkout process and comprehensive admin dashboard for managing products, categories, and orders.

## Key Features
- **Clothing-focused**: Products with sizes, colors, categories
- **Guest checkout**: No registration needed - just name, lastname, address, phone
- **Cash on delivery**: Simple payment process
- **Admin/Worker roles**: Management system for products and orders
- **Delivery integration**: Ready for delivery company APIs

---

## Database Schema

### 1. Users Table (Admin/Workers)
```sql
users
├── id (bigint, primary key)
├── name (string)
├── email (string, unique)
├── email_verified_at (timestamp, nullable)
├── password (string)
├── role (enum: 'admin', 'worker')
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 2. Categories Table
```sql
categories
├── id (bigint, primary key)
├── name (string)
├── slug (string, unique)
├── description (text, nullable)
├── image (string, nullable)
├── is_active (boolean, default: true)
├── sort_order (integer, default: 0)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 3. Products Table
```sql
products
├── id (bigint, primary key)
├── name (string)
├── slug (string, unique)
├── description (text)
├── short_description (string, nullable)
├── price (decimal: 10,2)
├── compare_price (decimal: 10,2, nullable) -- for sale prices
├── sku (string, unique)
├── category_id (bigint, foreign key)
├── is_active (boolean, default: true)
├── is_featured (boolean, default: false)
├── weight (decimal: 8,2, nullable) -- for shipping
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 4. Product Images Table
```sql
product_images
├── id (bigint, primary key)
├── product_id (bigint, foreign key)
├── image_path (string)
├── alt_text (string, nullable)
├── sort_order (integer, default: 0)
├── is_primary (boolean, default: false)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 5. Product Variants Table (Sizes & Colors)
```sql
product_variants
├── id (bigint, primary key)
├── product_id (bigint, foreign key)
├── size (string) -- XS, S, M, L, XL, XXL
├── color (string) -- Red, Blue, Black, etc.
├── color_code (string, nullable) -- #FF0000 for hex codes
├── sku (string, unique)
├── price (decimal: 10,2, nullable) -- if different from base price
├── stock_quantity (integer, default: 0)
├── is_active (boolean, default: true)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 6. Customers Table (Guest Checkout)
```sql
customers
├── id (bigint, primary key)
├── first_name (string)
├── last_name (string)
├── email (string, nullable)
├── phone (string)
├── address (text)
├── city (string)
├── postal_code (string, nullable)
├── country (string, default: 'Morocco')
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 7. Orders Table
```sql
orders
├── id (bigint, primary key)
├── order_number (string, unique) -- YM-2024-001
├── customer_id (bigint, foreign key)
├── status (enum: 'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')
├── payment_method (enum: 'cash_on_delivery')
├── payment_status (enum: 'pending', 'paid', 'failed')
├── subtotal (decimal: 10,2)
├── tax_amount (decimal: 10,2, default: 0)
├── shipping_amount (decimal: 10,2, default: 0)
├── total_amount (decimal: 10,2)
├── notes (text, nullable)
├── delivery_company (string, nullable)
├── tracking_number (string, nullable)
├── delivered_at (timestamp, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 8. Order Items Table
```sql
order_items
├── id (bigint, primary key)
├── order_id (bigint, foreign key)
├── product_id (bigint, foreign key)
├── product_variant_id (bigint, foreign key, nullable)
├── product_name (string) -- snapshot of product name
├── product_sku (string) -- snapshot of SKU
├── size (string, nullable)
├── color (string, nullable)
├── quantity (integer)
├── unit_price (decimal: 10,2)
├── total_price (decimal: 10,2)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 9. Cart Table (Session-based)
```sql
cart
├── id (string, primary key) -- session ID
├── user_id (bigint, nullable) -- if user is logged in
├── product_variant_id (bigint, foreign key)
├── quantity (integer)
├── created_at (timestamp)
└── updated_at (timestamp)
```

---

## API Routes & Endpoints

### Frontend Routes (Public)

#### Product Catalog
```
GET  /                    - Homepage with featured products
GET  /products            - Product listing with filters
GET  /products/{slug}     - Product detail page
GET  /category/{slug}     - Category products
GET  /search              - Product search
```

#### Shopping Cart
```
GET  /cart                - View cart
POST /cart/add            - Add item to cart
PUT  /cart/update/{id}    - Update cart item quantity
DELETE /cart/remove/{id}  - Remove item from cart
```

#### Checkout
```
GET  /checkout            - Checkout page
POST /checkout            - Process order
GET  /order/{number}      - Order confirmation
```

### Admin Routes (Protected)

#### Authentication
```
GET  /admin/login         - Admin login page
POST /admin/login         - Process login
POST /admin/logout        - Logout
```

#### Dashboard
```
GET  /admin               - Admin dashboard
GET  /admin/dashboard     - Dashboard statistics
```

#### Products Management
```
GET    /admin/products              - Products list
GET    /admin/products/create       - Create product form
POST   /admin/products              - Store product
GET    /admin/products/{id}         - Show product
GET    /admin/products/{id}/edit    - Edit product form
PUT    /admin/products/{id}         - Update product
DELETE /admin/products/{id}         - Delete product
```

#### Categories Management
```
GET    /admin/categories            - Categories list
GET    /admin/categories/create     - Create category form
POST   /admin/categories            - Store category
GET    /admin/categories/{id}/edit  - Edit category form
PUT    /admin/categories/{id}       - Update category
DELETE /admin/categories/{id}       - Delete category
```

#### Orders Management
```
GET    /admin/orders                - Orders list
GET    /admin/orders/{id}           - Order details
PUT    /admin/orders/{id}/status    - Update order status
POST   /admin/orders/{id}/tracking  - Add tracking info
```

#### Customers Management
```
GET    /admin/customers             - Customers list
GET    /admin/customers/{id}        - Customer details
```

---

## API Endpoints (JSON)

### Public API

#### Products
```
GET    /api/products                    - List products with pagination
GET    /api/products/{id}               - Get product details
GET    /api/categories                  - List categories
GET    /api/categories/{id}/products    - Get category products
GET    /api/search                      - Search products
```

#### Cart
```
GET    /api/cart                        - Get cart contents
POST   /api/cart/add                    - Add item to cart
PUT    /api/cart/update/{id}            - Update cart item
DELETE /api/cart/remove/{id}            - Remove cart item
```

#### Orders
```
POST   /api/orders                      - Create order
GET    /api/orders/{number}             - Get order by number
```

### Admin API

#### Products
```
GET    /api/admin/products              - List all products
POST   /api/admin/products              - Create product
GET    /api/admin/products/{id}         - Get product
PUT    /api/admin/products/{id}         - Update product
DELETE /api/admin/products/{id}         - Delete product
```

#### Categories
```
GET    /api/admin/categories            - List categories
POST   /api/admin/categories            - Create category
PUT    /api/admin/categories/{id}       - Update category
DELETE /api/admin/categories/{id}       - Delete category
```

#### Orders
```
GET    /api/admin/orders                - List orders
GET    /api/admin/orders/{id}           - Get order details
PUT    /api/admin/orders/{id}/status    - Update order status
```

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── OrderController.php
│   │   └── CustomerController.php
│   ├── Api/
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   └── OrderController.php
│   ├── ProductController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   └── HomeController.php
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Category.php
│   ├── ProductImage.php
│   ├── ProductVariant.php
│   ├── Customer.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Cart.php
├── Services/
│   ├── CartService.php
│   ├── OrderService.php
│   └── ImageService.php
└── Requests/
    ├── StoreProductRequest.php
    ├── UpdateProductRequest.php
    └── CheckoutRequest.php
```

---

## Key Features Implementation

### 1. Guest Checkout Flow
1. Customer browses products
2. Adds items to cart
3. Proceeds to checkout
4. Fills in: First Name, Last Name, Phone, Address
5. Confirms order
6. Receives order number
7. Admin processes order

### 2. Product Management
- Multiple images per product
- Size and color variants
- Stock management
- Category organization
- SEO-friendly URLs

### 3. Order Management
- Order status tracking
- Delivery company integration
- Tracking number management
- Customer communication

### 4. Admin Dashboard
- Sales statistics
- Order management
- Product inventory
- Customer database

---

## Technology Stack

- **Backend**: Laravel 10
- **Database**: MySQL
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS
- **Image Processing**: Intervention Image
- **Authentication**: Laravel Breeze

---

## Next Steps

1. ✅ Set up Laravel project
2. ✅ Install required packages
3. 🔄 Create database migrations
4. ⏳ Set up models and relationships
5. ⏳ Create controllers and services
6. ⏳ Build frontend templates
7. ⏳ Implement admin dashboard
8. ⏳ Add image upload functionality
9. ⏳ Test checkout flow
10. ⏳ Deploy and configure

---

*This documentation will be updated as the project progresses.*
