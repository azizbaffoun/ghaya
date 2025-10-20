# Yakine Mode - React Admin Dashboard API Documentation

## Base URL
```
https://localhost:8000/api/v1
```

## Authentication
All admin endpoints require Bearer token authentication. Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## Response Format
All API responses follow this structure:
```json
{
  "success": true|false,
  "message": "Success/Error message",
  "data": { ... },
  "pagination": { ... } // Only for paginated endpoints
}
```

---

## 1. Authentication APIs

### 1.1 Admin Login
**POST** `/auth/login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### 1.2 Admin Logout
**POST** `/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### 1.3 Get Current User
**GET** `/auth/me`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin"
  }
}
```

---

## 2. Dashboard APIs

### 2.1 Dashboard Statistics
**GET** `/admin/dashboard/stats`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `period` (optional): Number of days (default: 30)

**Response:**
```json
{
  "success": true,
  "data": {
    "orders": {
      "total": 150,
      "pending": 25,
      "confirmed": 45,
      "delivered": 70,
      "cancelled": 10,
      "recent": 30
    },
    "products": {
      "total": 500,
      "active": 450,
      "featured": 50,
      "in_stock": 400,
      "out_of_stock": 50
    },
    "customers": {
      "total": 200,
      "recent": 15
    },
    "categories": {
      "total": 25,
      "active": 20
    },
    "revenue": {
      "total": 50000.00,
      "recent": 15000.00,
      "average_order_value": 333.33
    }
  }
}
```

### 2.2 Recent Orders
**GET** `/admin/dashboard/recent-orders`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `limit` (optional): Number of orders to return (default: 10)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "ORD-2024-0001",
      "customer_name": "John Doe",
      "customer_email": "john@example.com",
      "total": 150.00,
      "status": "pending",
      "confirmation_status": "pending_confirmation",
      "created_at": "2024-01-15 10:30:00",
      "items_count": 3
    }
  ]
}
```

### 2.3 Top Products
**GET** `/admin/dashboard/top-products`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `limit` (optional): Number of products to return (default: 10)
- `period` (optional): Number of days (default: 30)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "sku": "SKU001",
      "total_sold": 50,
      "total_revenue": 5000.00
    }
  ]
}
```

### 2.4 Revenue Statistics
**GET** `/admin/dashboard/revenue`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `period` (optional): Number of days (default: 30)

**Response:**
```json
{
  "success": true,
  "data": {
    "total": 50000.00,
    "recent": 15000.00,
    "average_order_value": 333.33,
    "orders_count": 150,
    "recent_orders_count": 45,
    "daily": [
      {
        "date": "2024-01-15",
        "revenue": 2500.00
      }
    ]
  }
}
```

---

## 3. Products Management APIs

### 3.1 Get All Products
**GET** `/admin/products`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `category_id` (optional): Filter by category
- `stock_status` (optional): Filter by stock status (in_stock, out_of_stock, pre_order)
- `is_active` (optional): Filter by active status (true/false)
- `search` (optional): Search by name, description, or SKU
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "sku": "SKU001",
      "category_id": 1,
      "description": "Product description",
      "short_description": "Short description",
      "price": 99.99,
      "compare_price": 129.99,
      "stock_status": "in_stock",
      "is_active": true,
      "is_featured": false,
      "weight": 1.5,
      "sizes": ["S", "M", "L"],
      "colors": ["Red", "Blue"],
      "meta_title": "Meta title",
      "meta_description": "Meta description",
      "meta_keywords": "keywords",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "category": {
        "id": 1,
        "name": "Category Name"
      },
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ],
      "variants": [
        {
          "id": 1,
          "name": "Variant Name",
          "price": 99.99,
          "sku": "SKU001-RED-S"
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

### 3.2 Get Single Product
**GET** `/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "sku": "SKU001",
    "category_id": 1,
    "description": "Product description",
    "short_description": "Short description",
    "price": 99.99,
    "compare_price": 129.99,
    "stock_status": "in_stock",
    "is_active": true,
    "is_featured": false,
    "weight": 1.5,
    "sizes": ["S", "M", "L"],
    "colors": ["Red", "Blue"],
    "meta_title": "Meta title",
    "meta_description": "Meta description",
    "meta_keywords": "keywords",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "category": {
      "id": 1,
      "name": "Category Name"
    },
    "images": [
      {
        "id": 1,
        "url": "https://example.com/image.jpg",
        "is_primary": true
      }
    ],
    "variants": [
      {
        "id": 1,
        "name": "Variant Name",
        "price": 99.99,
        "sku": "SKU001-RED-S"
      }
    ]
  }
}
```

### 3.3 Create Product
**POST** `/admin/products`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Product Name",
  "sku": "SKU001",
  "category_id": 1,
  "description": "Product description",
  "short_description": "Short description",
  "price": 99.99,
  "compare_price": 129.99,
  "stock_status": "in_stock",
  "is_active": true,
  "is_featured": false,
  "weight": 1.5,
  "sizes": ["S", "M", "L"],
  "colors": ["Red", "Blue"],
  "meta_title": "Meta title",
  "meta_description": "Meta description",
  "meta_keywords": "keywords"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 1,
    "name": "Product Name",
    "sku": "SKU001",
    "category_id": 1,
    "description": "Product description",
    "short_description": "Short description",
    "price": 99.99,
    "compare_price": 129.99,
    "stock_status": "in_stock",
    "is_active": true,
    "is_featured": false,
    "weight": 1.5,
    "sizes": ["S", "M", "L"],
    "colors": ["Red", "Blue"],
    "meta_title": "Meta title",
    "meta_description": "Meta description",
    "meta_keywords": "keywords",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "category": {
      "id": 1,
      "name": "Category Name"
    },
    "images": [],
    "variants": []
  }
}
```

### 3.4 Update Product
**PUT** `/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Updated Product Name",
  "price": 89.99,
  "is_featured": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Product Name",
    "price": 89.99,
    "is_featured": true,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 3.5 Delete Product
**DELETE** `/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

### 3.6 Toggle Featured Status
**POST** `/admin/products/{id}/toggle-featured`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Featured status updated successfully",
  "data": {
    "id": 1,
    "is_featured": true
  }
}
```

---

## 4. Product Images Management APIs

### 4.1 Upload Product Image
**POST** `/admin/products/{id}/images`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body:**
```
image: [file]
is_primary: true|false
```

**Response:**
```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "id": 1,
    "url": "https://example.com/images/product-1.jpg",
    "is_primary": true,
    "product_id": 1
  }
}
```

### 4.2 Bulk Upload Images
**POST** `/admin/products/{id}/images/bulk`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body:**
```
images[]: [file1, file2, file3]
```

**Response:**
```json
{
  "success": true,
  "message": "Images uploaded successfully",
  "data": [
    {
      "id": 1,
      "url": "https://example.com/images/product-1-1.jpg",
      "is_primary": false,
      "product_id": 1
    },
    {
      "id": 2,
      "url": "https://example.com/images/product-1-2.jpg",
      "is_primary": false,
      "product_id": 1
    }
  ]
}
```

### 4.3 Delete Product Image
**DELETE** `/admin/products/{id}/images/{imageId}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Image deleted successfully"
}
```

### 4.4 Set Primary Image
**PUT** `/admin/products/{id}/images/{imageId}/primary`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Primary image updated successfully"
}
```

---

## 5. Product Variants Management APIs

### 5.1 Create Product Variant
**POST** `/admin/products/{id}/variants`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Red - Small",
  "sku": "SKU001-RED-S",
  "price": 99.99,
  "stock_quantity": 50,
  "is_active": true,
  "attributes": {
    "color": "Red",
    "size": "S"
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Product variant created successfully",
  "data": {
    "id": 1,
    "product_id": 1,
    "name": "Red - Small",
    "sku": "SKU001-RED-S",
    "price": 99.99,
    "stock_quantity": 50,
    "is_active": true,
    "attributes": {
      "color": "Red",
      "size": "S"
    },
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 5.2 Update Product Variant
**PUT** `/admin/products/{id}/variants/{variantId}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Red - Small",
  "price": 89.99,
  "stock_quantity": 45
}
```

**Response:**
```json
{
  "success": true,
  "message": "Product variant updated successfully",
  "data": {
    "id": 1,
    "name": "Red - Small",
    "price": 89.99,
    "stock_quantity": 45,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 5.3 Delete Product Variant
**DELETE** `/admin/products/{id}/variants/{variantId}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Product variant deleted successfully"
}
```

---

## 6. Categories Management APIs

### 6.1 Get All Categories
**GET** `/admin/categories`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `parent_id` (optional): Filter by parent category (null for root categories)
- `sort_by` (optional): Sort field (default: name)
- `sort_order` (optional): Sort direction (asc/desc, default: asc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics",
      "description": "Electronic products",
      "image": "https://example.com/category.jpg",
      "is_active": true,
      "parent_id": null,
      "order": 1,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "children": [
        {
          "id": 2,
          "name": "Smartphones",
          "slug": "smartphones",
          "parent_id": 1,
          "products_count": 25
        }
      ],
      "products_count": 50
    }
  ]
}
```

### 6.2 Create Category
**POST** `/admin/categories`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Electronics",
  "slug": "electronics",
  "description": "Electronic products",
  "parent_id": null,
  "is_active": true,
  "order": 1
}
```

**Response:**
```json
{
  "success": true,
  "message": "Category created successfully",
  "data": {
    "id": 1,
    "name": "Electronics",
    "slug": "electronics",
    "description": "Electronic products",
    "image": null,
    "is_active": true,
    "parent_id": null,
    "order": 1,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

### 6.3 Update Category
**PUT** `/admin/categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Updated Electronics",
  "description": "Updated description"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Category updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Electronics",
    "description": "Updated description",
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 6.4 Delete Category
**DELETE** `/admin/categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Category deleted successfully"
}
```

### 6.5 Upload Category Image
**POST** `/admin/categories/{id}/image`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body:**
```
image: [file]
```

**Response:**
```json
{
  "success": true,
  "message": "Category image uploaded successfully",
  "data": {
    "id": 1,
    "image": "https://example.com/categories/electronics.jpg"
  }
}
```

---

## 7. Orders Management APIs

### 7.1 Get All Orders
**GET** `/admin/orders`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `status` (optional): Filter by status (pending, processing, shipped, delivered, cancelled)
- `customer_email` (optional): Filter by customer email
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "ORD-2024-0001",
      "customer_id": 1,
      "customer_email": "customer@example.com",
      "customer_first_name": "John",
      "customer_last_name": "Doe",
      "customer_phone": "+1234567890",
      "shipping_address": "123 Main St",
      "shipping_city": "New York",
      "shipping_state": "NY",
      "shipping_postal_code": "10001",
      "shipping_country": "USA",
      "subtotal": 100.00,
      "shipping_cost": 10.00,
      "total": 110.00,
      "status": "pending",
      "confirmation_status": "pending_confirmation",
      "payment_status": "pending",
      "payment_method": "cash_on_delivery",
      "notes": "Please deliver after 5 PM",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "items": [
        {
          "id": 1,
          "product_id": 1,
          "product_variant_id": 1,
          "product_name": "Product Name",
          "variant_name": "Red - Small",
          "quantity": 2,
          "unit_price": 50.00,
          "total_price": 100.00
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

### 7.2 Search Orders
**GET** `/admin/orders/search`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `search` (optional): Search term
- `status` (optional): Filter by status
- `confirmation_status` (optional): Filter by confirmation status
- `date_from` (optional): Start date (YYYY-MM-DD)
- `date_to` (optional): End date (YYYY-MM-DD)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "ORD-2024-0001",
      "customer_first_name": "John",
      "customer_last_name": "Doe",
      "customer_email": "customer@example.com",
      "customer_phone": "+1234567890",
      "shipping_address": "123 Main St",
      "shipping_city": "New York",
      "shipping_state": "NY",
      "shipping_postal_code": "10001",
      "shipping_country": "USA",
      "subtotal": 100.00,
      "shipping_cost": 10.00,
      "total": 110.00,
      "status": "pending",
      "confirmation_status": "pending_confirmation",
      "created_at": "15/01/2024 10:30",
      "order_items": [
        {
          "id": 1,
          "product_name": "Product Name",
          "variant_name": "Red - Small",
          "quantity": 2,
          "unit_price": 50.00,
          "total_price": 100.00
        }
      ]
    }
  ],
  "count": 1
}
```

### 7.3 Get Single Order
**GET** `/admin/orders/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "ORD-2024-0001",
    "customer_id": 1,
    "customer_email": "customer@example.com",
    "customer_first_name": "John",
    "customer_last_name": "Doe",
    "customer_phone": "+1234567890",
    "shipping_address": "123 Main St",
    "shipping_city": "New York",
    "shipping_state": "NY",
    "shipping_postal_code": "10001",
    "shipping_country": "USA",
    "subtotal": 100.00,
    "shipping_cost": 10.00,
    "total": 110.00,
    "status": "pending",
    "confirmation_status": "pending_confirmation",
    "payment_status": "pending",
    "payment_method": "cash_on_delivery",
    "notes": "Please deliver after 5 PM",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "product_variant_id": 1,
        "product_name": "Product Name",
        "variant_name": "Red - Small",
        "quantity": 2,
        "unit_price": 50.00,
        "total_price": 100.00
      }
    ]
  }
}
```

### 7.4 Update Order
**PUT** `/admin/orders/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "status": "processing",
  "customer_first_name": "John",
  "customer_last_name": "Doe",
  "customer_email": "john@example.com",
  "customer_phone": "+1234567890",
  "shipping_address": "123 Main St",
  "billing_address": "123 Main St"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order updated successfully",
  "data": {
    "id": 1,
    "status": "processing",
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 7.5 Update Order Status
**PUT** `/admin/orders/{id}/status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "status": "delivered",
  "confirmation_status": "confirmed"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order status updated successfully",
  "data": {
    "id": 1,
    "status": "delivered",
    "confirmation_status": "confirmed",
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 7.6 Delete Order
**DELETE** `/admin/orders/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Order deleted successfully"
}
```

---

## 8. Customers Management APIs

### 8.1 Get All Customers
**GET** `/admin/customers`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `search` (optional): Search by name or email
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone": "+1234567890",
      "address": "123 Main St",
      "city": "New York",
      "state": "NY",
      "postal_code": "10001",
      "country": "USA",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "orders_count": 5
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

### 8.2 Get Single Customer
**GET** `/admin/customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "USA",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

### 8.3 Create Customer
**POST** `/admin/customers`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "address": "123 Main St",
  "city": "New York",
  "state": "NY",
  "postal_code": "10001",
  "country": "USA"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Customer created successfully",
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "USA",
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 8.4 Update Customer
**PUT** `/admin/customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Smith",
  "phone": "+1234567891"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Customer updated successfully",
  "data": {
    "id": 1,
    "first_name": "John",
    "last_name": "Smith",
    "phone": "+1234567891",
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 8.5 Delete Customer
**DELETE** `/admin/customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Customer deleted successfully"
}
```

### 8.6 Get Customer Orders
**GET** `/admin/customers/{id}/orders`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "ORD-2024-0001",
      "total": 110.00,
      "status": "delivered",
      "created_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

---

## 9. Banners Management APIs

### 9.1 Get All Banners
**GET** `/admin/banners`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `is_active` (optional): Filter by active status (true/false)
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Summer Sale",
      "description": "Up to 50% off on all items",
      "image": "https://example.com/banners/summer-sale.jpg",
      "link": "https://example.com/sale",
      "is_active": true,
      "order": 1,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 50
  }
}
```

### 9.2 Create Banner
**POST** `/admin/banners`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "title": "Summer Sale",
  "description": "Up to 50% off on all items",
  "link": "https://example.com/sale",
  "is_active": true,
  "order": 1
}
```

**Response:**
```json
{
  "success": true,
  "message": "Banner created successfully",
  "data": {
    "id": 1,
    "title": "Summer Sale",
    "description": "Up to 50% off on all items",
    "image": null,
    "link": "https://example.com/sale",
    "is_active": true,
    "order": 1,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 9.3 Update Banner
**PUT** `/admin/banners/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "title": "Updated Summer Sale",
  "is_active": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Banner updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Summer Sale",
    "is_active": false,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 9.4 Delete Banner
**DELETE** `/admin/banners/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Banner deleted successfully"
}
```

### 9.5 Toggle Banner Status
**PATCH** `/admin/banners/{id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Banner status updated successfully",
  "data": {
    "id": 1,
    "is_active": false
  }
}
```

### 9.6 Upload Banner Images
**POST** `/admin/banners/{id}/images`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body:**
```
images[]: [file1, file2]
```

**Response:**
```json
{
  "success": true,
  "message": "Banner images uploaded successfully",
  "data": [
    {
      "id": 1,
      "url": "https://example.com/banners/summer-sale-1.jpg",
      "banner_id": 1
    },
    {
      "id": 2,
      "url": "https://example.com/banners/summer-sale-2.jpg",
      "banner_id": 1
    }
  ]
}
```

---

## 10. Languages Management APIs

### 10.1 Get All Languages
**GET** `/admin/languages`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "English",
      "code": "en",
      "is_active": true,
      "is_default": true,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    },
    {
      "id": 2,
      "name": "French",
      "code": "fr",
      "is_active": true,
      "is_default": false,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### 10.2 Create Language
**POST** `/admin/languages`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Spanish",
  "code": "es",
  "is_active": true,
  "is_default": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Language created successfully",
  "data": {
    "id": 3,
    "name": "Spanish",
    "code": "es",
    "is_active": true,
    "is_default": false,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 10.3 Update Language
**PUT** `/admin/languages/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Updated Spanish",
  "is_active": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Language updated successfully",
  "data": {
    "id": 3,
    "name": "Updated Spanish",
    "is_active": false,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 10.4 Delete Language
**DELETE** `/admin/languages/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Language deleted successfully"
}
```

### 10.5 Toggle Language Status
**PATCH** `/admin/languages/{id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Language status updated successfully",
  "data": {
    "id": 3,
    "is_active": true
  }
}
```

### 10.6 Set Default Language
**POST** `/admin/languages/{id}/set-default`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Default language updated successfully",
  "data": {
    "id": 3,
    "is_default": true
  }
}
```

---

## 11. Homepage Sections Management APIs

### 11.1 Get All Homepage Sections
**GET** `/admin/homepage-sections`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `type` (optional): Filter by section type
- `is_active` (optional): Filter by active status (true/false)
- `sort_by` (optional): Sort field (default: order)
- `sort_order` (optional): Sort direction (asc/desc, default: asc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "hero_banner",
      "title": "Welcome to Our Store",
      "content": "Discover amazing products",
      "settings": {
        "background_color": "#ffffff",
        "text_color": "#000000"
      },
      "is_active": true,
      "order": 1,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### 11.2 Create Homepage Section
**POST** `/admin/homepage-sections`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "type": "featured_products",
  "title": "Featured Products",
  "content": "Check out our featured products",
  "settings": {
    "products_count": 8,
    "show_prices": true
  },
  "is_active": true,
  "order": 2
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage section created successfully",
  "data": {
    "id": 2,
    "type": "featured_products",
    "title": "Featured Products",
    "content": "Check out our featured products",
    "settings": {
      "products_count": 8,
      "show_prices": true
    },
    "is_active": true,
    "order": 2,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 11.3 Update Homepage Section
**PUT** `/admin/homepage-sections/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "title": "Updated Featured Products",
  "is_active": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage section updated successfully",
  "data": {
    "id": 2,
    "title": "Updated Featured Products",
    "is_active": false,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 11.4 Delete Homepage Section
**DELETE** `/admin/homepage-sections/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage section deleted successfully"
}
```

### 11.5 Toggle Homepage Section Status
**PUT** `/admin/homepage-sections/{id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage section status updated successfully",
  "data": {
    "id": 2,
    "is_active": true
  }
}
```

### 11.6 Reorder Homepage Sections
**PUT** `/admin/homepage-sections/reorder`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "sections": [
    {
      "id": 1,
      "order": 1
    },
    {
      "id": 2,
      "order": 2
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage sections reordered successfully"
}
```

---

## 12. Homepage Assets Management APIs

### 12.1 Get All Homepage Assets
**GET** `/admin/homepage-assets`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Hero Image",
      "type": "image",
      "url": "https://example.com/assets/hero.jpg",
      "alt_text": "Hero banner image",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### 12.2 Create Homepage Asset
**POST** `/admin/homepage-assets`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Logo",
  "type": "image",
  "alt_text": "Company logo",
  "is_active": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage asset created successfully",
  "data": {
    "id": 2,
    "name": "Logo",
    "type": "image",
    "url": null,
    "alt_text": "Company logo",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### 12.3 Update Homepage Asset
**PUT** `/admin/homepage-assets/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "name": "Updated Logo",
  "is_active": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage asset updated successfully",
  "data": {
    "id": 2,
    "name": "Updated Logo",
    "is_active": false,
    "updated_at": "2024-01-15T11:30:00Z"
  }
}
```

### 12.4 Delete Homepage Asset
**DELETE** `/admin/homepage-assets/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage asset deleted successfully"
}
```

### 12.5 Bulk Upload Homepage Assets
**POST** `/admin/homepage-assets/bulk-upload`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
Accept: application/json
```

**Request Body:**
```
assets[]: [file1, file2, file3]
names[]: ["Asset 1", "Asset 2", "Asset 3"]
types[]: ["image", "image", "video"]
```

**Response:**
```json
{
  "success": true,
  "message": "Homepage assets uploaded successfully",
  "data": [
    {
      "id": 3,
      "name": "Asset 1",
      "type": "image",
      "url": "https://example.com/assets/asset-1.jpg",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z"
    },
    {
      "id": 4,
      "name": "Asset 2",
      "type": "image",
      "url": "https://example.com/assets/asset-2.jpg",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

---

## 13. Public APIs (No Authentication Required)

### 13.1 Get Products (Public)
**GET** `/products`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `category_id` (optional): Filter by category
- `stock_status` (optional): Filter by stock status
- `is_active` (optional): Filter by active status
- `search` (optional): Search by name, description, or SKU
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "sku": "SKU001",
      "price": 99.99,
      "compare_price": 129.99,
      "stock_status": "in_stock",
      "is_featured": true,
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ],
      "variants": [
        {
          "id": 1,
          "name": "Red - Small",
          "price": 99.99,
          "sku": "SKU001-RED-S"
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

### 13.2 Get Featured Products
**GET** `/products/featured`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `per_page` (optional): Items per page (default: 15)
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Featured Product",
      "sku": "SKU001",
      "price": 99.99,
      "is_featured": true,
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 50
  }
}
```

### 13.3 Get New Products
**GET** `/products/new`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `per_page` (optional): Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "New Product",
      "sku": "SKU001",
      "price": 99.99,
      "created_at": "2024-01-15T10:30:00Z",
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 30
  }
}
```

### 13.4 Get Related Products
**GET** `/products/related/{id}`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `per_page` (optional): Items per page (default: 8)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Related Product 1",
      "sku": "SKU002",
      "price": 89.99,
      "images": [
        {
          "id": 2,
          "url": "https://example.com/image2.jpg",
          "is_primary": true
        }
      ]
    }
  ]
}
```

### 13.5 Get Categories (Public)
**GET** `/categories`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `parent_id` (optional): Filter by parent category (null for root categories)
- `sort_by` (optional): Sort field (default: name)
- `sort_order` (optional): Sort direction (asc/desc, default: asc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics",
      "image": "https://example.com/category.jpg",
      "is_active": true,
      "children": [
        {
          "id": 2,
          "name": "Smartphones",
          "slug": "smartphones",
          "products_count": 25
        }
      ],
      "products_count": 50
    }
  ]
}
```

### 13.6 Get Banners (Public)
**GET** `/banners`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Summer Sale",
      "description": "Up to 50% off on all items",
      "image": "https://example.com/banners/summer-sale.jpg",
      "link": "https://example.com/sale",
      "is_active": true,
      "order": 1
    }
  ]
}
```

### 13.7 Get Languages (Public)
**GET** `/languages`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "English",
      "code": "en",
      "is_active": true,
      "is_default": true
    },
    {
      "id": 2,
      "name": "French",
      "code": "fr",
      "is_active": true,
      "is_default": false
    }
  ]
}
```

### 13.8 Get Default Language
**GET** `/languages/default`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "English",
    "code": "en",
    "is_active": true,
    "is_default": true
  }
}
```

### 13.9 Search Products
**GET** `/search`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `q` (required): Search query
- `category_id` (optional): Filter by category
- `min_price` (optional): Minimum price
- `max_price` (optional): Maximum price
- `sort_by` (optional): Sort field (name, price, created_at)
- `sort_order` (optional): Sort direction (asc/desc, default: desc)
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Search Result Product",
      "sku": "SKU001",
      "price": 99.99,
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ]
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 50
  }
}
```

### 13.10 Get Search Suggestions
**GET** `/search/suggestions`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `q` (required): Search query
- `limit` (optional): Number of suggestions (default: 5)

**Response:**
```json
{
  "success": true,
  "data": [
    "iPhone",
    "Samsung Galaxy",
    "MacBook Pro",
    "iPad",
    "AirPods"
  ]
}
```

### 13.11 Get Product Filters
**GET** `/products/filters`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "Electronics",
        "products_count": 50
      }
    ],
    "price_ranges": [
      {
        "min": 0,
        "max": 100,
        "label": "Under $100"
      },
      {
        "min": 100,
        "max": 500,
        "label": "$100 - $500"
      }
    ],
    "brands": [
      {
        "name": "Apple",
        "products_count": 25
      },
      {
        "name": "Samsung",
        "products_count": 20
      }
    ]
  }
}
```

---

## 14. Cart APIs (Session-based)

### 14.1 Get Cart
**GET** `/cart`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "product_variant_id": 1,
        "quantity": 2,
        "product": {
          "id": 1,
          "name": "Product Name",
          "sku": "SKU001",
          "price": 99.99,
          "images": [
            {
              "id": 1,
              "url": "https://example.com/image.jpg",
              "is_primary": true
            }
          ]
        },
        "product_variant": {
          "id": 1,
          "name": "Red - Small",
          "price": 99.99,
          "sku": "SKU001-RED-S"
        }
      }
    ],
    "total": 199.98,
    "item_count": 2,
    "unique_items": 1
  }
}
```

### 14.2 Add to Cart
**POST** `/cart/add`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "product_id": 1,
  "product_variant_id": 1,
  "quantity": 2
}
```

**Response:**
```json
{
  "success": true,
  "message": "Item added to cart",
  "data": {
    "id": 1,
    "product_id": 1,
    "product_variant_id": 1,
    "quantity": 2,
    "product": {
      "id": 1,
      "name": "Product Name",
      "sku": "SKU001",
      "price": 99.99,
      "images": [
        {
          "id": 1,
          "url": "https://example.com/image.jpg",
          "is_primary": true
        }
      ]
    },
    "product_variant": {
      "id": 1,
      "name": "Red - Small",
      "price": 99.99,
      "sku": "SKU001-RED-S"
    }
  }
}
```

### 14.3 Update Cart Item
**PUT** `/cart/update/{id}`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "quantity": 3
}
```

**Response:**
```json
{
  "success": true,
  "message": "Cart item updated",
  "data": {
    "id": 1,
    "quantity": 3,
    "product": {
      "id": 1,
      "name": "Product Name",
      "price": 99.99
    }
  }
}
```

### 14.4 Remove from Cart
**DELETE** `/cart/remove/{id}`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Item removed from cart"
}
```

### 14.5 Clear Cart
**DELETE** `/cart/clear`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "message": "Cart cleared successfully"
}
```

### 14.6 Get Cart Count
**GET** `/cart/count`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "item_count": 5,
    "unique_items": 3
  }
}
```

---

## 15. Checkout APIs

### 15.1 Validate Checkout
**POST** `/checkout/validate`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "product_variant_id": 1,
      "quantity": 2
    }
  ],
  "shipping_address": {
    "first_name": "John",
    "last_name": "Doe",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "postal_code": "10001",
    "country": "USA"
  }
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "valid": true,
    "subtotal": 199.98,
    "shipping_cost": 10.00,
    "total": 209.98,
    "available_items": [
      {
        "product_id": 1,
        "product_variant_id": 1,
        "quantity": 2,
        "available": true
      }
    ]
  }
}
```

### 15.2 Calculate Checkout
**POST** `/checkout/calculate`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "product_variant_id": 1,
      "quantity": 2
    }
  ],
  "shipping_method": "standard",
  "coupon_code": "SAVE10"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "subtotal": 199.98,
    "shipping_cost": 10.00,
    "discount": 20.00,
    "tax": 19.00,
    "total": 208.98,
    "breakdown": {
      "items": 199.98,
      "shipping": 10.00,
      "discount": -20.00,
      "tax": 19.00
    }
  }
}
```

---

## 16. Order APIs (Public)

### 16.1 Create Order
**POST** `/orders`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "customer_email": "customer@example.com",
  "customer_first_name": "John",
  "customer_last_name": "Doe",
  "customer_phone": "+1234567890",
  "shipping_address": "123 Main St",
  "shipping_city": "New York",
  "shipping_state": "NY",
  "shipping_postal_code": "10001",
  "shipping_country": "USA",
  "subtotal": 199.98,
  "shipping_cost": 10.00,
  "total": 209.98,
  "payment_method": "cash_on_delivery",
  "notes": "Please deliver after 5 PM",
  "items": [
    {
      "product_id": 1,
      "product_variant_id": 1,
      "product_name": "Product Name",
      "variant_name": "Red - Small",
      "quantity": 2,
      "unit_price": 99.99,
      "total_price": 199.98
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "order_number": "ORD-2024-0001",
    "customer_email": "customer@example.com",
    "total": 209.98,
    "status": "pending",
    "created_at": "2024-01-15T10:30:00Z",
    "items": [
      {
        "id": 1,
        "product_name": "Product Name",
        "variant_name": "Red - Small",
        "quantity": 2,
        "unit_price": 99.99,
        "total_price": 199.98
      }
    ]
  }
}
```

### 16.2 Get Order by Number
**GET** `/orders/{orderNumber}`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "ORD-2024-0001",
    "customer_email": "customer@example.com",
    "customer_first_name": "John",
    "customer_last_name": "Doe",
    "customer_phone": "+1234567890",
    "shipping_address": "123 Main St",
    "shipping_city": "New York",
    "shipping_state": "NY",
    "shipping_postal_code": "10001",
    "shipping_country": "USA",
    "subtotal": 199.98,
    "shipping_cost": 10.00,
    "total": 209.98,
    "status": "pending",
    "confirmation_status": "pending_confirmation",
    "payment_status": "pending",
    "payment_method": "cash_on_delivery",
    "notes": "Please deliver after 5 PM",
    "created_at": "2024-01-15T10:30:00Z",
    "items": [
      {
        "id": 1,
        "product_name": "Product Name",
        "variant_name": "Red - Small",
        "quantity": 2,
        "unit_price": 99.99,
        "total_price": 199.98
      }
    ]
  }
}
```

---

## 17. Homepage APIs (Public)

### 17.1 Get Homepage Data
**GET** `/homepage`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "sections": [
      {
        "id": 1,
        "type": "hero_banner",
        "title": "Welcome to Our Store",
        "content": "Discover amazing products",
        "settings": {
          "background_color": "#ffffff",
          "text_color": "#000000"
        },
        "is_active": true,
        "order": 1
      },
      {
        "id": 2,
        "type": "featured_products",
        "title": "Featured Products",
        "content": "Check out our featured products",
        "settings": {
          "products_count": 8,
          "show_prices": true
        },
        "is_active": true,
        "order": 2
      }
    ],
    "banners": [
      {
        "id": 1,
        "title": "Summer Sale",
        "description": "Up to 50% off on all items",
        "image": "https://example.com/banners/summer-sale.jpg",
        "link": "https://example.com/sale",
        "is_active": true,
        "order": 1
      }
    ]
  }
}
```

### 17.2 Get Homepage Section by Type
**GET** `/homepage/sections/{type}`

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "type": "featured_products",
    "title": "Featured Products",
    "content": "Check out our featured products",
    "settings": {
      "products_count": 8,
      "show_prices": true
    },
    "is_active": true,
    "order": 2
  }
}
```

---

## Error Responses

All endpoints may return the following error responses:

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["The field name is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Access denied. Admin role required."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "The given data was invalid",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Server error",
  "error": "Error details"
}
```

---

## Authentication Notes

1. **Admin Authentication**: All admin endpoints require a valid Bearer token in the Authorization header
2. **Token Format**: `Authorization: Bearer {token}`
3. **Token Expiration**: Tokens may expire and need to be refreshed
4. **Role-based Access**: Some endpoints may require specific roles (admin, worker)
5. **Session-based Cart**: Cart operations work with session IDs for non-authenticated users

---

## Rate Limiting

- API requests are rate limited to 60 requests per minute per user/IP
- Exceeded rate limits will return a 429 Too Many Requests response

---

## File Uploads

- Image uploads support common formats: JPG, PNG, GIF, WebP
- Maximum file size: 10MB per file
- Use `multipart/form-data` content type for file uploads
- Files are automatically processed and optimized

---

## Pagination

- All list endpoints support pagination
- Default page size: 15 items
- Maximum page size: 100 items
- Pagination info is included in the response

---

## Search and Filtering

- Most list endpoints support search and filtering
- Search is case-insensitive
- Multiple filters can be combined
- Sort options are available for most endpoints

---

## Data Validation

- All input data is validated on the server
- Validation errors are returned with specific field messages
- Required fields must be provided
- Data types must match the expected format

---

This documentation covers all the APIs available for your React admin dashboard. Each endpoint includes the required headers, request/response formats, and error handling. Make sure to implement proper error handling and loading states in your React application.
