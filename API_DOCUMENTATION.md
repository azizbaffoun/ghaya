# API Documentation

## Overview
This document provides comprehensive documentation for the Yakine Mode API. The API is organized into public endpoints (no authentication required), customer endpoints (customer authentication required), and admin endpoints (admin authentication required).

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication
- **Customer Authentication**: Uses Laravel Sanctum tokens
- **Admin Authentication**: Uses Laravel Sanctum tokens with admin middleware
- **Public Endpoints**: No authentication required

## Response Format
All API responses follow this format:
```json
{
    "success": true|false,
    "message": "Optional message",
    "data": {}, // Response data
    "pagination": {} // For paginated responses
}
```

## Error Handling
Errors are returned with appropriate HTTP status codes and error messages:
```json
{
    "success": false,
    "message": "Error description",
    "error": "Detailed error information"
}
```

---

## Public API Endpoints

### Authentication

#### Register Customer
```http
POST /api/v1/auth/register
```

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123",
    "address": "123 Main St",
    "city": "Casablanca",
    "country": "Morocco"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Customer registered successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "full_name": "John Doe"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

#### Login Customer
```http
POST /api/v1/auth/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Forgot Password
```http
POST /api/v1/auth/forgot-password
```

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

#### Reset Password
```http
POST /api/v1/auth/reset-password
```

**Request Body:**
```json
{
    "token": "reset_token",
    "email": "john@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### Products

#### Get All Products
```http
GET /api/v1/products
```

**Query Parameters:**
- `category_id` - Filter by category
- `stock_status` - Filter by stock status (in_stock, out_of_stock, pre_order)
- `is_active` - Filter by active status (true/false)
- `search` - Search in name, description, or SKU
- `sort_by` - Sort field (name, price, created_at)
- `sort_order` - Sort direction (asc, desc)
- `per_page` - Items per page (default: 15)

#### Get Featured Products
```http
GET /api/v1/products/featured
```

#### Get New Products
```http
GET /api/v1/products/new
```

#### Get Related Products
```http
GET /api/v1/products/{id}/related
```

#### Get Product Details
```http
GET /api/v1/products/{id}
```

#### Get Product Variants
```http
GET /api/v1/products/{id}/variants
```

### Categories

#### Get All Categories
```http
GET /api/v1/categories
```

#### Get Category Details
```http
GET /api/v1/categories/{id}
```

#### Get Category Products
```http
GET /api/v1/categories/{id}/products
```

### Banners

#### Get All Banners
```http
GET /api/v1/banners
```

**Query Parameters:**
- `type` - Filter by banner type (hero, banner, slider)
- `position` - Filter by position (top, middle, bottom, sidebar)

#### Get Banner Details
```http
GET /api/v1/banners/{id}
```

### Languages

#### Get All Languages
```http
GET /api/v1/languages
```

#### Get Default Language
```http
GET /api/v1/languages/default
```

### Cart (Session-based)

#### Get Cart Items
```http
GET /api/v1/cart
```

#### Add Item to Cart
```http
POST /api/v1/cart/add
```

**Request Body:**
```json
{
    "product_id": 1,
    "product_variant_id": 2,
    "quantity": 2
}
```

#### Update Cart Item
```http
PUT /api/v1/cart/update/{id}
```

**Request Body:**
```json
{
    "quantity": 3
}
```

#### Remove Cart Item
```http
DELETE /api/v1/cart/remove/{id}
```

#### Clear Cart
```http
DELETE /api/v1/cart/clear
```

#### Get Cart Count
```http
GET /api/v1/cart/count
```

### Checkout

#### Validate Cart
```http
POST /api/v1/checkout/validate
```

#### Calculate Totals
```http
POST /api/v1/checkout/calculate
```

**Request Body:**
```json
{
    "shipping_city": "Casablanca",
    "shipping_country": "Morocco"
}
```

### Orders

#### Create Order
```http
POST /api/v1/orders
```

**Request Body:**
```json
{
    "customer_email": "john@example.com",
    "customer_first_name": "John",
    "customer_last_name": "Doe",
    "customer_phone": "+1234567890",
    "shipping_address": "123 Main St",
    "shipping_city": "Casablanca",
    "shipping_country": "Morocco",
    "subtotal": 100.00,
    "shipping_cost": 30.00,
    "total": 130.00,
    "payment_method": "cash_on_delivery",
    "items": [
        {
            "product_id": 1,
            "product_variant_id": 2,
            "product_name": "Product Name",
            "variant_name": "Red - Large",
            "quantity": 2,
            "unit_price": 50.00,
            "total_price": 100.00
        }
    ]
}
```

#### Get Order Details
```http
GET /api/v1/orders/{orderNumber}
```

### Search

#### Global Search
```http
GET /api/v1/search
```

**Query Parameters:**
- `q` - Search query
- `type` - Search type (all, products, categories)
- `limit` - Maximum results (default: 20)

#### Search Suggestions
```http
GET /api/v1/search/suggestions
```

**Query Parameters:**
- `q` - Search query (minimum 2 characters)
- `limit` - Maximum suggestions (default: 10)

#### Get Product Filters
```http
GET /api/v1/products/filters
```

### Homepage

#### Get Homepage Content
```http
GET /api/v1/homepage
```

#### Get Homepage Section
```http
GET /api/v1/homepage/sections/{type}
```

---

## Customer API Endpoints
*Requires customer authentication*

### Customer Authentication

#### Logout
```http
POST /api/v1/auth/logout
```

#### Get Current User
```http
GET /api/v1/auth/me
```

#### Change Password
```http
POST /api/v1/auth/change-password
```

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### Customer Profile

#### Get Profile
```http
GET /api/v1/customer/profile
```

#### Update Profile
```http
PUT /api/v1/customer/profile
```

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "phone": "+1234567890",
    "address": "123 Main St",
    "city": "Casablanca"
}
```

#### Get Order History
```http
GET /api/v1/customer/orders
```

**Query Parameters:**
- `status` - Filter by order status
- `sort_by` - Sort field
- `sort_order` - Sort direction
- `per_page` - Items per page

#### Get Addresses
```http
GET /api/v1/customer/addresses
```

#### Add Address
```http
POST /api/v1/customer/addresses
```

### Customer Avatar

#### Upload Avatar
```http
POST /api/v1/customer/avatar
```

**Request Body:** Multipart form with `avatar` file

#### Remove Avatar
```http
DELETE /api/v1/customer/avatar
```

---

## Admin API Endpoints
*Requires admin authentication*

### Products Management

#### Create Product
```http
POST /api/v1/admin/products
```

#### Update Product
```http
PUT /api/v1/admin/products/{id}
```

#### Delete Product
```http
DELETE /api/v1/admin/products/{id}
```

#### Toggle Featured Status
```http
POST /api/v1/admin/products/{id}/toggle-featured
```

### Product Images

#### Upload Single Image
```http
POST /api/v1/admin/products/{id}/images
```

#### Bulk Upload Images
```http
POST /api/v1/admin/products/{id}/images/bulk
```

#### Delete Image
```http
DELETE /api/v1/admin/products/{id}/images/{imageId}
```

#### Set Primary Image
```http
PUT /api/v1/admin/products/{id}/images/{imageId}/primary
```

### Product Variants

#### Create Variant
```http
POST /api/v1/admin/products/{id}/variants
```

#### Update Variant
```http
PUT /api/v1/admin/products/{id}/variants/{variantId}
```

#### Delete Variant
```http
DELETE /api/v1/admin/products/{id}/variants/{variantId}
```

### Categories Management

#### Create Category
```http
POST /api/v1/admin/categories
```

#### Update Category
```http
PUT /api/v1/admin/categories/{id}
```

#### Delete Category
```http
DELETE /api/v1/admin/categories/{id}
```

#### Upload Category Image
```http
POST /api/v1/admin/categories/{id}/image
```

### Customers Management

#### Create Customer
```http
POST /api/v1/admin/customers
```

#### Update Customer
```http
PUT /api/v1/admin/customers/{id}
```

#### Delete Customer
```http
DELETE /api/v1/admin/customers/{id}
```

#### Get Customer Orders
```http
GET /api/v1/admin/customers/{id}/orders
```

### Orders Management

#### Get All Orders
```http
GET /api/v1/admin/orders
```

#### Search Orders
```http
GET /api/v1/admin/orders/search
```

#### Update Order
```http
PUT /api/v1/admin/orders/{id}
```

#### Delete Order
```http
DELETE /api/v1/admin/orders/{id}
```

#### Update Order Status
```http
PUT /api/v1/admin/orders/{id}/status
```

### Banners Management

#### Create Banner
```http
POST /api/v1/admin/banners
```

#### Update Banner
```http
PUT /api/v1/admin/banners/{id}
```

#### Delete Banner
```http
DELETE /api/v1/admin/banners/{id}
```

#### Toggle Banner Status
```http
PATCH /api/v1/admin/banners/{id}/toggle
```

### Languages Management

#### Create Language
```http
POST /api/v1/admin/languages
```

#### Update Language
```http
PUT /api/v1/admin/languages/{id}
```

#### Delete Language
```http
DELETE /api/v1/admin/languages/{id}
```

#### Toggle Language Status
```http
PATCH /api/v1/admin/languages/{id}/toggle
```

#### Set Default Language
```http
POST /api/v1/admin/languages/{id}/set-default
```

### Dashboard Statistics

#### Get Dashboard Stats
```http
GET /api/v1/admin/dashboard/stats
```

**Query Parameters:**
- `period` - Period in days (default: 30)

#### Get Recent Orders
```http
GET /api/v1/admin/dashboard/recent-orders
```

**Query Parameters:**
- `limit` - Number of orders (default: 10)

#### Get Top Products
```http
GET /api/v1/admin/dashboard/top-products
```

**Query Parameters:**
- `limit` - Number of products (default: 10)
- `period` - Period in days (default: 30)

#### Get Revenue Statistics
```http
GET /api/v1/admin/dashboard/revenue
```

**Query Parameters:**
- `period` - Period in days (default: 30)

---

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting

API requests are rate limited to prevent abuse. The limits are:
- Public endpoints: 100 requests per minute
- Customer endpoints: 200 requests per minute
- Admin endpoints: 500 requests per minute

## Pagination

Paginated responses include pagination metadata:
```json
{
    "success": true,
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150
    }
}
```

## File Uploads

File uploads are supported for:
- Product images (JPEG, PNG, JPG, GIF, max 10MB)
- Category images (JPEG, PNG, JPG, GIF, max 10MB)
- Banner images (JPEG, PNG, JPG, GIF, max 2MB)
- Customer avatars (JPEG, PNG, JPG, GIF, max 2MB)
- Language flags (JPEG, PNG, JPG, GIF, max 2MB)

Use `multipart/form-data` content type for file uploads.

## Examples

### Complete Order Flow

1. **Add items to cart:**
```bash
curl -X POST "https://your-domain.com/api/v1/cart/add" \
  -H "Content-Type: application/json" \
  -d '{"product_id": 1, "quantity": 2}'
```

2. **Validate cart:**
```bash
curl -X POST "https://your-domain.com/api/v1/checkout/validate"
```

3. **Calculate totals:**
```bash
curl -X POST "https://your-domain.com/api/v1/checkout/calculate" \
  -H "Content-Type: application/json" \
  -d '{"shipping_city": "Casablanca", "shipping_country": "Morocco"}'
```

4. **Create order:**
```bash
curl -X POST "https://your-domain.com/api/v1/orders" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_email": "customer@example.com",
    "customer_first_name": "John",
    "customer_last_name": "Doe",
    "shipping_address": "123 Main St",
    "shipping_city": "Casablanca",
    "subtotal": 100.00,
    "shipping_cost": 30.00,
    "total": 130.00,
    "items": [...]
  }'
```

### Customer Authentication Flow

1. **Register:**
```bash
curl -X POST "https://your-domain.com/api/v1/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

2. **Login:**
```bash
curl -X POST "https://your-domain.com/api/v1/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com", "password": "password123"}'
```

3. **Use token in subsequent requests:**
```bash
curl -X GET "https://your-domain.com/api/v1/customer/profile" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

This completes the comprehensive API documentation for the Yakine Mode e-commerce platform.