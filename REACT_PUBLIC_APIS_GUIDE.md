# React Frontend - Public APIs Guide

## Base Configuration

### API Base URL
```
http://localhost:8000/api/v1
```

### Environment Variables
Create a `.env` file in your React project:
```env
REACT_APP_API_BASE_URL=http://localhost:8000/api/v1
REACT_APP_API_URL=http://localhost:8000
```

### Headers
All requests should include:
```javascript
{
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```

### Response Format
All APIs return this consistent format:
```json
{
  "success": true|false,
  "message": "Optional message",
  "data": {},
  "pagination": {} // Only for paginated endpoints
}
```

---

## 1. Products APIs

### 1.1 Get All Products
**GET** `/products`

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15, max: 100)
- `category_id` (optional): Filter by category ID
- `stock_status` (optional): Filter by stock status (`in_stock`, `out_of_stock`, `pre_order`)
- `is_active` (optional): Filter by active status (`true`/`false`)
- `search` (optional): Search in name, description, or SKU
- `sort_by` (optional): Sort field (`name`, `price`, `created_at`, `updated_at`)
- `sort_order` (optional): Sort direction (`asc`/`desc`, default: `desc`)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Premium Cotton T-Shirt",
      "sku": "TSH-001",
      "description": "High-quality cotton t-shirt with modern fit",
      "short_description": "Comfortable cotton t-shirt",
      "price": 29.99,
      "compare_price": 39.99,
      "stock_status": "in_stock",
      "is_active": true,
      "is_featured": true,
      "weight": 0.2,
      "meta_title": "Premium Cotton T-Shirt - Yakine Mode",
      "meta_description": "Shop our premium cotton t-shirt collection",
      "meta_keywords": "t-shirt, cotton, premium, fashion",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "category": {
        "id": 1,
        "name": "T-Shirts",
        "slug": "t-shirts",
        "image": "http://localhost:8000/storage/categories/t-shirts.jpg"
      },
      "images": [
        {
          "id": 1,
          "image_path": "products/2024/01/tshirt-001-main.jpg",
          "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-main.jpg",
          "alt_text": "Premium Cotton T-Shirt - Front View",
          "color": "White",
          "is_primary": true
        },
        {
          "id": 2,
          "image_path": "products/2024/01/tshirt-001-back.jpg",
          "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-back.jpg",
          "alt_text": "Premium Cotton T-Shirt - Back View",
          "color": "White",
          "is_primary": false
        }
      ],
      "variants": [
        {
          "id": 1,
          "name": "White - Small",
          "sku": "TSH-001-WHITE-S",
          "price": 29.99,
          "stock_quantity": 50,
          "is_active": true,
          "attributes": {
            "color": "White",
            "size": "S"
          },
          "created_at": "2024-01-15T10:30:00Z"
        },
        {
          "id": 2,
          "name": "White - Medium",
          "sku": "TSH-001-WHITE-M",
          "price": 29.99,
          "stock_quantity": 75,
          "is_active": true,
          "attributes": {
            "color": "White",
            "size": "M"
          },
          "created_at": "2024-01-15T10:30:00Z"
        },
        {
          "id": 3,
          "name": "Black - Small",
          "sku": "TSH-001-BLACK-S",
          "price": 29.99,
          "stock_quantity": 30,
          "is_active": true,
          "attributes": {
            "color": "Black",
            "size": "S"
          },
          "created_at": "2024-01-15T10:30:00Z"
        }
      ],
      "sizes": ["S", "M", "L", "XL"],
      "colors": ["White", "Black", "Navy", "Gray"]
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

### 1.2 Get Featured Products
**GET** `/products/featured`

**Query Parameters:**
- `per_page` (optional): Items per page (default: 15)
- `sort_by` (optional): Sort field (default: `created_at`)
- `sort_order` (optional): Sort direction (default: `desc`)

**Response:** Same structure as Get All Products, but only featured products.

### 1.3 Get New Products
**GET** `/products/new`

**Query Parameters:**
- `per_page` (optional): Items per page (default: 15)

**Response:** Same structure as Get All Products, but only recently created products.

### 1.4 Get Related Products
**GET** `/products/related/{id}`

**Query Parameters:**
- `per_page` (optional): Items per page (default: 8)

**Response:** Same structure as Get All Products, but products related to the specified product.

### 1.5 Get Product Details
**GET** `/products/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Premium Cotton T-Shirt",
    "sku": "TSH-001",
    "description": "High-quality cotton t-shirt with modern fit. Made from 100% organic cotton, this t-shirt offers superior comfort and durability. Perfect for everyday wear or casual outings.",
    "short_description": "Comfortable cotton t-shirt",
    "price": 29.99,
    "compare_price": 39.99,
    "stock_status": "in_stock",
    "is_active": true,
    "is_featured": true,
    "weight": 0.2,
    "meta_title": "Premium Cotton T-Shirt - Yakine Mode",
    "meta_description": "Shop our premium cotton t-shirt collection",
    "meta_keywords": "t-shirt, cotton, premium, fashion",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "category": {
      "id": 1,
      "name": "T-Shirts",
      "slug": "t-shirts",
      "description": "Comfortable and stylish t-shirts",
      "image": "http://localhost:8000/storage/categories/t-shirts.jpg",
      "is_active": true,
      "parent_id": null,
      "order": 1,
      "products_count": 25
    },
    "images": [
      {
        "id": 1,
        "image_path": "products/2024/01/tshirt-001-main.jpg",
        "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-main.jpg",
        "alt_text": "Premium Cotton T-Shirt - Front View",
        "color": "White",
        "is_primary": true
      },
      {
        "id": 2,
        "image_path": "products/2024/01/tshirt-001-back.jpg",
        "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-back.jpg",
        "alt_text": "Premium Cotton T-Shirt - Back View",
        "color": "White",
        "is_primary": false
      },
      {
        "id": 3,
        "image_path": "products/2024/01/tshirt-001-side.jpg",
        "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-side.jpg",
        "alt_text": "Premium Cotton T-Shirt - Side View",
        "color": "White",
        "is_primary": false
      }
    ],
    "variants": [
      {
        "id": 1,
        "name": "White - Small",
        "sku": "TSH-001-WHITE-S",
        "price": 29.99,
        "stock_quantity": 50,
        "is_active": true,
        "attributes": {
          "color": "White",
          "size": "S"
        },
        "created_at": "2024-01-15T10:30:00Z"
      },
      {
        "id": 2,
        "name": "White - Medium",
        "sku": "TSH-001-WHITE-M",
        "price": 29.99,
        "stock_quantity": 75,
        "is_active": true,
        "attributes": {
          "color": "White",
          "size": "M"
        },
        "created_at": "2024-01-15T10:30:00Z"
      },
      {
        "id": 3,
        "name": "Black - Small",
        "sku": "TSH-001-BLACK-S",
        "price": 29.99,
        "stock_quantity": 30,
        "is_active": true,
        "attributes": {
          "color": "Black",
          "size": "S"
        },
        "created_at": "2024-01-15T10:30:00Z"
      },
      {
        "id": 4,
        "name": "Black - Medium",
        "sku": "TSH-001-BLACK-M",
        "price": 29.99,
        "stock_quantity": 45,
        "is_active": true,
        "attributes": {
          "color": "Black",
          "size": "M"
        },
        "created_at": "2024-01-15T10:30:00Z"
      }
    ],
    "sizes": ["S", "M", "L", "XL"],
    "colors": ["White", "Black", "Navy", "Gray"],
    "available_sizes": ["S", "M", "L", "XL"],
    "available_colors": ["White", "Black"]
  }
}
```

### 1.6 Get Product Variants
**GET** `/products/{id}/variants`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "name": "White - Small",
      "sku": "TSH-001-WHITE-S",
      "price": 29.99,
      "stock_quantity": 50,
      "is_active": true,
      "attributes": {
        "color": "White",
        "size": "S"
      },
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

---

## 2. Categories APIs

### 2.1 Get All Categories
**GET** `/categories`

**Query Parameters:**
- `parent_id` (optional): Filter by parent category (null for root categories)
- `sort_by` (optional): Sort field (default: `name`)
- `sort_order` (optional): Sort direction (default: `asc`)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Clothing",
      "slug": "clothing",
      "description": "All clothing items",
      "image": "http://localhost:8000/storage/categories/clothing.jpg",
      "is_active": true,
      "parent_id": null,
      "order": 1,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "children": [
        {
          "id": 2,
          "name": "T-Shirts",
          "slug": "t-shirts",
          "parent_id": 1,
          "products_count": 25,
          "is_active": true
        },
        {
          "id": 3,
          "name": "Jeans",
          "slug": "jeans",
          "parent_id": 1,
          "products_count": 15,
          "is_active": true
        }
      ],
      "products_count": 40
    },
    {
      "id": 4,
      "name": "Accessories",
      "slug": "accessories",
      "description": "Fashion accessories",
      "image": "http://localhost:8000/storage/categories/accessories.jpg",
      "is_active": true,
      "parent_id": null,
      "order": 2,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "children": [],
      "products_count": 20
    }
  ]
}
```

### 2.2 Get Category Details
**GET** `/categories/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Clothing",
    "slug": "clothing",
    "description": "All clothing items including t-shirts, jeans, and more",
    "image": "http://localhost:8000/storage/categories/clothing.jpg",
    "is_active": true,
    "parent_id": null,
    "order": 1,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "children": [
      {
        "id": 2,
        "name": "T-Shirts",
        "slug": "t-shirts",
        "parent_id": 1,
        "products_count": 25,
        "is_active": true
      }
    ],
    "products_count": 40
  }
}
```

### 2.3 Get Category Products
**GET** `/categories/{id}/products`

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `stock_status` (optional): Filter by stock status
- `is_active` (optional): Filter by active status
- `search` (optional): Search in name, description, or SKU
- `sort_by` (optional): Sort field (default: `created_at`)
- `sort_order` (optional): Sort direction (default: `desc`)

**Response:** Same structure as Get All Products, but filtered by category.

---

## 3. Banners APIs

### 3.1 Get All Banners
**GET** `/banners`

**Query Parameters:**
- `type` (optional): Filter by banner type (`hero`, `promotional`, `category`)
- `position` (optional): Filter by position (`top`, `middle`, `bottom`, `sidebar`)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Summer Collection 2024",
      "subtitle": "Up to 50% off on all items",
      "image": "http://localhost:8000/storage/banners/summer-collection.jpg",
      "cta_text": "Shop Now",
      "cta_link": "/products?category=summer",
      "type": "hero",
      "is_active": true,
      "sort_order": 1,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    },
    {
      "id": 2,
      "title": "New Arrivals",
      "subtitle": "Discover our latest collection",
      "image": "http://localhost:8000/storage/banners/new-arrivals.jpg",
      "cta_text": "View Collection",
      "cta_link": "/products/new",
      "type": "promotional",
      "is_active": true,
      "sort_order": 2,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### 3.2 Get Banner Details
**GET** `/banners/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Summer Collection 2024",
    "subtitle": "Up to 50% off on all items",
    "image": "http://localhost:8000/storage/banners/summer-collection.jpg",
    "cta_text": "Shop Now",
    "cta_link": "/products?category=summer",
    "type": "hero",
    "is_active": true,
    "sort_order": 1,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

---

## 4. Homepage APIs

### 4.1 Get Homepage Content
**GET** `/homepage`

**Response:**
```json
{
  "success": true,
  "data": {
    "sections": [
      {
        "id": 1,
        "type": "hero_banner",
        "title": "Welcome to Yakine Mode",
        "content": "Discover amazing products and exclusive deals",
        "settings": {
          "background_color": "#ffffff",
          "text_color": "#000000",
          "button_color": "#3B82F6"
        },
        "is_visible": true,
        "order": 1,
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
      },
      {
        "id": 2,
        "type": "featured_products",
        "title": "Featured Products",
        "content": "Check out our best-selling items",
        "settings": {
          "products_count": 8,
          "show_prices": true,
          "show_ratings": true
        },
        "is_visible": true,
        "order": 2,
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
      },
      {
        "id": 3,
        "type": "categories_grid",
        "title": "Shop by Category",
        "content": "Browse our product categories",
        "settings": {
          "columns": 4,
          "show_product_count": true
        },
        "is_visible": true,
        "order": 3,
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
      }
    ],
    "banners": [
      {
        "id": 1,
        "title": "Summer Collection 2024",
        "subtitle": "Up to 50% off on all items",
        "image": "http://localhost:8000/storage/banners/summer-collection.jpg",
        "cta_text": "Shop Now",
        "cta_link": "/products?category=summer",
        "type": "hero",
        "is_active": true,
        "sort_order": 1
      }
    ]
  }
}
```

### 4.2 Get Homepage Section by Type
**GET** `/homepage/sections/{type}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "type": "featured_products",
    "title": "Featured Products",
    "content": "Check out our best-selling items",
    "settings": {
      "products_count": 8,
      "show_prices": true,
      "show_ratings": true
    },
    "is_visible": true,
    "order": 2,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

---

## 5. Search APIs

### 5.1 Global Search
**GET** `/search`

**Query Parameters:**
- `q` (required): Search query
- `type` (optional): Search type (`all`, `products`, `categories`)
- `limit` (optional): Maximum results (default: 20)

**Response:**
```json
{
  "success": true,
  "data": {
    "products": [
      {
        "id": 1,
        "name": "Premium Cotton T-Shirt",
        "sku": "TSH-001",
        "price": 29.99,
        "images": [
          {
            "id": 1,
            "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-main.jpg",
            "is_primary": true
          }
        ],
        "category": {
          "id": 1,
          "name": "T-Shirts"
        }
      }
    ],
    "categories": [
      {
        "id": 1,
        "name": "T-Shirts",
        "slug": "t-shirts",
        "products_count": 25
      }
    ],
    "total": 1
  }
}
```

### 5.2 Search Suggestions
**GET** `/search/suggestions`

**Query Parameters:**
- `q` (required): Search query (minimum 2 characters)
- `limit` (optional): Maximum suggestions (default: 10)

**Response:**
```json
{
  "success": true,
  "data": [
    "t-shirt",
    "cotton t-shirt",
    "premium t-shirt",
    "white t-shirt",
    "black t-shirt"
  ]
}
```

### 5.3 Get Product Filters
**GET** `/products/filters`

**Response:**
```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "T-Shirts",
        "products_count": 25
      },
      {
        "id": 2,
        "name": "Jeans",
        "products_count": 15
      }
    ],
    "price_ranges": [
      {
        "min": 0,
        "max": 50,
        "label": "Under $50"
      },
      {
        "min": 50,
        "max": 100,
        "label": "$50 - $100"
      },
      {
        "min": 100,
        "max": 200,
        "label": "$100 - $200"
      }
    ],
    "brands": [
      {
        "name": "Yakine Mode",
        "products_count": 50
      }
    ],
    "colors": [
      {
        "name": "White",
        "products_count": 15
      },
      {
        "name": "Black",
        "products_count": 12
      },
      {
        "name": "Navy",
        "products_count": 8
      }
    ],
    "sizes": [
      {
        "name": "S",
        "products_count": 20
      },
      {
        "name": "M",
        "products_count": 25
      },
      {
        "name": "L",
        "products_count": 18
      },
      {
        "name": "XL",
        "products_count": 10
      }
    ]
  }
}
```

---

## 6. Languages APIs

### 6.1 Get All Languages
**GET** `/languages`

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
    },
    {
      "id": 3,
      "name": "Arabic",
      "code": "ar",
      "is_active": true,
      "is_default": false,
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### 6.2 Get Default Language
**GET** `/languages/default`

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

---

## 7. Cart APIs (Optional - for server-side cart)

### 7.1 Get Cart
**GET** `/cart`

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
          "name": "Premium Cotton T-Shirt",
          "sku": "TSH-001",
          "price": 29.99,
          "images": [
            {
              "id": 1,
              "url": "http://localhost:8000/storage/products/2024/01/tshirt-001-main.jpg",
              "is_primary": true
            }
          ]
        },
        "product_variant": {
          "id": 1,
          "name": "White - Small",
          "sku": "TSH-001-WHITE-S",
          "price": 29.99,
          "attributes": {
            "color": "White",
            "size": "S"
          }
        }
      }
    ],
    "total": 59.98,
    "item_count": 2,
    "unique_items": 1
  }
}
```

### 7.2 Add to Cart
**POST** `/cart/add`

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
      "name": "Premium Cotton T-Shirt",
      "price": 29.99
    }
  }
}
```

### 7.3 Update Cart Item
**PUT** `/cart/update/{id}`

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
      "name": "Premium Cotton T-Shirt",
      "price": 29.99
    }
  }
}
```

### 7.4 Remove from Cart
**DELETE** `/cart/remove/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Item removed from cart"
}
```

### 7.5 Clear Cart
**DELETE** `/cart/clear`

**Response:**
```json
{
  "success": true,
  "message": "Cart cleared successfully"
}
```

### 7.6 Get Cart Count
**GET** `/cart/count`

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

## 8. Checkout APIs

### 8.1 Validate Checkout
**POST** `/checkout/validate`

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
    "subtotal": 59.98,
    "shipping_cost": 10.00,
    "total": 69.98,
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

### 8.2 Calculate Totals
**POST** `/checkout/calculate`

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
    "subtotal": 59.98,
    "shipping_cost": 10.00,
    "discount": 6.00,
    "tax": 6.40,
    "total": 70.38,
    "breakdown": {
      "items": 59.98,
      "shipping": 10.00,
      "discount": -6.00,
      "tax": 6.40
    }
  }
}
```

---

## 9. Orders APIs

### 9.1 Create Order
**POST** `/orders`

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
  "subtotal": 59.98,
  "shipping_cost": 10.00,
  "total": 69.98,
  "payment_method": "cash_on_delivery",
  "notes": "Please deliver after 5 PM",
  "items": [
    {
      "product_id": 1,
      "product_variant_id": 1,
      "product_name": "Premium Cotton T-Shirt",
      "variant_name": "White - Small",
      "quantity": 2,
      "unit_price": 29.99,
      "total_price": 59.98
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
    "total": 69.98,
    "status": "pending",
    "created_at": "2024-01-15T10:30:00Z",
    "items": [
      {
        "id": 1,
        "product_name": "Premium Cotton T-Shirt",
        "variant_name": "White - Small",
        "quantity": 2,
        "unit_price": 29.99,
        "total_price": 59.98
      }
    ]
  }
}
```

### 9.2 Get Order by Number
**GET** `/orders/{orderNumber}`

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
    "subtotal": 59.98,
    "shipping_cost": 10.00,
    "total": 69.98,
    "status": "pending",
    "confirmation_status": "pending_confirmation",
    "payment_status": "pending",
    "payment_method": "cash_on_delivery",
    "notes": "Please deliver after 5 PM",
    "created_at": "2024-01-15T10:30:00Z",
    "items": [
      {
        "id": 1,
        "product_name": "Premium Cotton T-Shirt",
        "variant_name": "White - Small",
        "quantity": 2,
        "unit_price": 29.99,
        "total_price": 59.98
      }
    ]
  }
}
```

---

## Error Handling

### Common Error Responses

**400 Bad Request:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Resource not found"
}
```

**422 Unprocessable Entity:**
```json
{
  "success": false,
  "message": "The given data was invalid",
  "errors": {
    "quantity": ["The quantity must be at least 1."]
  }
}
```

**500 Internal Server Error:**
```json
{
  "success": false,
  "message": "Server error",
  "error": "Error details"
}
```

---

## Rate Limiting

- **Public endpoints**: 100 requests per minute
- **Cart endpoints**: 200 requests per minute
- **Search endpoints**: 50 requests per minute

Rate limit exceeded responses:
```json
{
  "success": false,
  "message": "Too many requests",
  "retry_after": 60
}
```

---

## Image URLs

All images are served from:
```
http://localhost:8000/storage/{path}
```

Examples:
- Product images: `http://localhost:8000/storage/products/2024/01/tshirt-001-main.jpg`
- Category images: `http://localhost:8000/storage/categories/t-shirts.jpg`
- Banner images: `http://localhost:8000/storage/banners/summer-collection.jpg`

---

## CORS Configuration

The API is configured to allow requests from:
- `http://localhost:3000`
- `http://localhost:3001`
- `http://localhost:5173` (Vite dev server)
- `http://127.0.0.1:3000`
- `http://127.0.0.1:3001`
- `http://127.0.0.1:5173`

---

## React Implementation Tips

### 1. API Service Setup
```javascript
// api.js
const API_BASE_URL = process.env.REACT_APP_API_BASE_URL;

export const api = {
  get: (endpoint) => fetch(`${API_BASE_URL}${endpoint}`),
  post: (endpoint, data) => fetch(`${API_BASE_URL}${endpoint}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
};
```

### 2. Error Handling
```javascript
const handleApiResponse = async (response) => {
  const data = await response.json();
  if (!data.success) {
    throw new Error(data.message);
  }
  return data;
};
```

### 3. Image Loading
```javascript
const getImageUrl = (path) => {
  return `http://localhost:8000/storage/${path}`;
};
```

### 4. Pagination
```javascript
const usePagination = (data, pagination) => {
  return {
    items: data,
    currentPage: pagination.current_page,
    totalPages: pagination.last_page,
    totalItems: pagination.total,
    itemsPerPage: pagination.per_page
  };
};
```

This comprehensive guide covers all the public APIs your React team will need to integrate with the Laravel backend. Each endpoint includes detailed request/response examples with all the data structures you'll receive.
