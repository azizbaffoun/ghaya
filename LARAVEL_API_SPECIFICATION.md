# Laravel Backend API Specification
## Yakine Mode Fashion Flow - E-commerce Platform

---

## Table of Contents
1. [Overview](#overview)
2. [Current Frontend Features](#current-frontend-features)
3. [Required API Endpoints](#required-api-endpoints)
4. [Data Models & Database Schema](#data-models--database-schema)
5. [API Response Formats](#api-response-formats)
6. [Homepage Editor System](#homepage-editor-system)
7. [Authentication & Authorization](#authentication--authorization)
8. [File Upload & Storage](#file-upload--storage)
9. [CORS Configuration](#cors-configuration)
10. [Implementation Notes](#implementation-notes)

---

## Overview

This document outlines the API requirements for the Laravel backend to support the React-based e-commerce frontend for Yakine Mode Fashion.

**Frontend Tech Stack:**
- React 18 with TypeScript
- Vite build tool
- React Router for navigation
- TanStack Query (React Query) for data fetching
- i18next for internationalization (English, French, Arabic)
- Tailwind CSS + Shadcn UI components

**Backend Requirements:**
- Laravel (latest stable version recommended)
- RESTful API architecture
- JSON responses
- File storage for product images and assets
- CORS enabled for frontend communication

---

## Current Frontend Features

### Pages & Routes

1. **Home Page** (`/`)
   - Hero banner section (editable)
   - Featured products carousel
   - Category showcase (Women, Accessories)

2. **Category Page** (`/category/:slug`)
   - Category header
   - Subcategory sidebar navigation
   - Product grid
   - Filtering by subcategories

3. **Product Detail Page** (`/product/:slug`)
   - Product images gallery
   - Product information (name, price, description)
   - Size and color selection
   - Quantity selector
   - Add to cart functionality
   - Buy now (direct checkout) functionality

4. **Shopping Cart** (`/cart`)
   - Cart items list
   - Quantity management
   - Item removal
   - Order summary
   - Clear cart functionality

5. **Checkout** (`/checkout`)
   - Customer information form (name, phone, address)
   - Order summary
   - Order submission

### Data Structures (TypeScript Interfaces)

```typescript
interface Category {
  id: string;
  name: string;
  slug: string;
  subcategories?: Category[];
}

interface Product {
  id: string;
  name: string;
  slug: string;
  price: number;
  images: string[];
  categoryId: string;
  description: string;
  sizes: string[];
  colors: Array<{ 
    name: string; 
    hex: string; 
    image?: string 
  }>;
  featured?: boolean;
}

interface CartItem {
  product: Product;
  size: string;
  color: string;
  quantity: number;
}

interface Order {
  items: CartItem[];
  customer: {
    name: string;
    phone: string;
    address: string;
  };
  total: number;
}
```

---

## Required API Endpoints

### Base URL
- **Development:** `http://localhost:8000/api`
- **Production:** `https://your-domain.com/api`

---

### 1. Products

#### GET `/api/products`
Get all products with optional filtering.

**Query Parameters:**
- `featured` (boolean, optional): Filter featured products
- `category` (string, optional): Filter by category slug
- `subcategory` (string, optional): Filter by subcategory ID
- `limit` (integer, optional): Limit results
- `page` (integer, optional): Pagination

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "1",
      "name": "Elegant Silk Dress",
      "slug": "elegant-silk-dress",
      "price": 189.99,
      "images": [
        "https://your-domain.com/storage/products/dress-1.jpg",
        "https://your-domain.com/storage/products/dress-2.jpg"
      ],
      "category_id": "1-1",
      "description": "A luxurious silk dress perfect for any occasion...",
      "sizes": ["XS", "S", "M", "L", "XL"],
      "colors": [
        {
          "name": "Burgundy",
          "hex": "#7C2D4D",
          "image": "https://your-domain.com/storage/products/dress-burgundy.jpg"
        },
        {
          "name": "Black",
          "hex": "#000000",
          "image": "https://your-domain.com/storage/products/dress-black.jpg"
        }
      ],
      "featured": true,
      "created_at": "2025-01-15T10:00:00Z",
      "updated_at": "2025-01-15T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 25,
    "per_page": 10
  }
}
```

---

#### GET `/api/products/{slug}`
Get a single product by slug.

**URL Parameters:**
- `slug` (string, required): Product slug

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "1",
    "name": "Elegant Silk Dress",
    "slug": "elegant-silk-dress",
    "price": 189.99,
    "images": [
      "https://your-domain.com/storage/products/dress-1.jpg"
    ],
    "category_id": "1-1",
    "description": "A luxurious silk dress perfect for any occasion...",
    "sizes": ["XS", "S", "M", "L", "XL"],
    "colors": [
      {
        "name": "Burgundy",
        "hex": "#7C2D4D",
        "image": "https://your-domain.com/storage/products/dress-burgundy.jpg"
      }
    ],
    "featured": true,
    "stock_status": "in_stock",
    "created_at": "2025-01-15T10:00:00Z",
    "updated_at": "2025-01-15T10:00:00Z"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Product not found"
}
```

---

### 2. Categories

#### GET `/api/categories`
Get all categories with subcategories.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "1",
      "name": "Women",
      "slug": "women",
      "image": "https://your-domain.com/storage/categories/women.jpg",
      "subcategories": [
        {
          "id": "1-1",
          "name": "Dresses",
          "slug": "dresses",
          "product_count": 15
        },
        {
          "id": "1-2",
          "name": "Tops",
          "slug": "tops",
          "product_count": 22
        }
      ],
      "product_count": 50
    },
    {
      "id": "3",
      "name": "Accessories",
      "slug": "accessories",
      "image": "https://your-domain.com/storage/categories/accessories.jpg",
      "subcategories": [
        {
          "id": "3-1",
          "name": "Bags",
          "slug": "bags",
          "product_count": 12
        }
      ],
      "product_count": 30
    }
  ]
}
```

---

#### GET `/api/categories/{slug}`
Get a single category by slug with its subcategories.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "1",
    "name": "Women",
    "slug": "women",
    "image": "https://your-domain.com/storage/categories/women.jpg",
    "description": "Explore our women's collection",
    "subcategories": [
      {
        "id": "1-1",
        "name": "Dresses",
        "slug": "dresses",
        "product_count": 15
      }
    ],
    "product_count": 50
  }
}
```

---

### 3. Orders

#### POST `/api/orders`
Create a new order.

**Request Body:**
```json
{
  "customer": {
    "name": "Jane Doe",
    "phone": "+1234567890",
    "address": "123 Main St, City, Country, 12345"
  },
  "items": [
    {
      "product_id": "1",
      "size": "M",
      "color": "Burgundy",
      "quantity": 2,
      "price": 189.99
    },
    {
      "product_id": "2",
      "size": "One Size",
      "color": "Black",
      "quantity": 1,
      "price": 249.99
    }
  ],
  "total": 629.97,
  "notes": "Please deliver before 5 PM"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "order_id": "ORD-2025-00123",
    "status": "pending",
    "customer": {
      "name": "Jane Doe",
      "phone": "+1234567890",
      "address": "123 Main St, City, Country, 12345"
    },
    "items": [
      {
        "product_id": "1",
        "product_name": "Elegant Silk Dress",
        "size": "M",
        "color": "Burgundy",
        "quantity": 2,
        "price": 189.99,
        "subtotal": 379.98
      }
    ],
    "total": 629.97,
    "created_at": "2025-01-15T14:30:00Z"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "customer.name": ["The name field is required"],
    "items": ["At least one item is required"]
  }
}
```

---

### 4. Homepage Content

#### GET `/api/homepage`
Get all homepage sections for dynamic content.

**Response:**
```json
{
  "success": true,
  "data": {
    "sections": [
      {
        "id": 1,
        "type": "hero",
        "title": "Discover Your Style",
        "subtitle": "Explore our curated collection",
        "description": null,
        "content": {
          "buttons": [
            {
              "text": "Shop Women",
              "link": "/category/women",
              "variant": "primary"
            },
            {
              "text": "Shop Accessories",
              "link": "/category/accessories",
              "variant": "outline"
            }
          ]
        },
        "images": [
          "https://your-domain.com/storage/homepage/hero-banner.jpg"
        ],
        "settings": {
          "background_color": null,
          "text_color": "#ffffff",
          "overlay_opacity": 0.6,
          "height": "70vh"
        },
        "is_active": true,
        "sort_order": 1
      },
      {
        "id": 2,
        "type": "featured_products",
        "title": "Featured Collection",
        "subtitle": null,
        "description": null,
        "content": {
          "product_ids": ["1", "2", "3", "5"],
          "display_style": "carousel"
        },
        "images": [],
        "settings": {
          "items_per_row": 4,
          "show_carousel_arrows": true
        },
        "is_active": true,
        "sort_order": 2
      },
      {
        "id": 3,
        "type": "category_showcase",
        "title": "Shop by Category",
        "subtitle": null,
        "description": null,
        "content": {
          "categories": [
            {
              "id": "1",
              "name": "Women",
              "image": "https://your-domain.com/storage/categories/women-showcase.jpg",
              "link": "/category/women"
            },
            {
              "id": "3",
              "name": "Accessories",
              "image": "https://your-domain.com/storage/categories/accessories-showcase.jpg",
              "link": "/category/accessories"
            }
          ]
        },
        "images": [],
        "settings": {
          "layout": "grid",
          "columns": 2
        },
        "is_active": true,
        "sort_order": 3
      }
    ]
  }
}
```

---

### 5. Homepage Assets (For Admin Use)

#### GET `/api/admin/homepage/assets`
Get all uploaded homepage assets.

**Query Parameters:**
- `category` (string, optional): Filter by category (hero, banner, icon, etc.)
- `type` (string, optional): Filter by type (image, video, icon)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Hero Banner 2025.jpg",
      "url": "https://your-domain.com/storage/homepage-assets/hero/uuid-123.jpg",
      "alt_text": "Fashion collection banner",
      "type": "image",
      "category": "hero",
      "metadata": {
        "original_name": "Hero Banner 2025.jpg",
        "size": 2048576,
        "mime_type": "image/jpeg",
        "dimensions": {
          "width": 1920,
          "height": 1080
        }
      },
      "created_at": "2025-01-15T10:00:00Z"
    }
  ]
}
```

---

#### POST `/api/admin/homepage/assets/upload`
Upload a new asset for homepage.

**Request (multipart/form-data):**
- `file` (file, required): The file to upload
- `category` (string, required): Asset category (hero, banner, icon, etc.)
- `alt_text` (string, optional): Alt text for the image

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Asset uploaded successfully",
  "data": {
    "id": 1,
    "name": "Hero Banner 2025.jpg",
    "url": "https://your-domain.com/storage/homepage-assets/hero/uuid-123.jpg",
    "alt_text": "Fashion collection banner",
    "type": "image",
    "category": "hero"
  }
}
```

---

#### DELETE `/api/admin/homepage/assets/{id}`
Delete an asset.

**Response:**
```json
{
  "success": true,
  "message": "Asset deleted successfully"
}
```

---

### 6. Homepage Sections Management (Admin)

#### GET `/api/admin/homepage/sections`
Get all homepage sections (including inactive ones).

**Response:** Same structure as `/api/homepage` but includes inactive sections.

---

#### POST `/api/admin/homepage/sections`
Create a new homepage section.

**Request Body:**
```json
{
  "section_type": "hero",
  "title": "New Collection",
  "subtitle": "Spring 2025",
  "description": null,
  "content": {
    "buttons": [
      {
        "text": "Shop Now",
        "link": "/category/new",
        "variant": "primary"
      }
    ]
  },
  "images": [
    "homepage-assets/hero/uuid-456.jpg"
  ],
  "settings": {
    "background_color": "#f5f5f5",
    "text_color": "#000000"
  },
  "is_active": true
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Section created successfully",
  "data": {
    "id": 4,
    "section_type": "hero",
    "title": "New Collection",
    "sort_order": 4,
    "is_active": true,
    "created_at": "2025-01-15T15:00:00Z"
  }
}
```

---

#### PUT `/api/admin/homepage/sections/{id}`
Update an existing homepage section.

**Request Body:** Same as POST (partial updates allowed)

**Response:**
```json
{
  "success": true,
  "message": "Section updated successfully",
  "data": {
    "id": 4,
    "section_type": "hero",
    "title": "Updated Title",
    "updated_at": "2025-01-15T16:00:00Z"
  }
}
```

---

#### DELETE `/api/admin/homepage/sections/{id}`
Delete a homepage section.

**Response:**
```json
{
  "success": true,
  "message": "Section deleted successfully"
}
```

---

#### POST `/api/admin/homepage/sections/reorder`
Reorder homepage sections.

**Request Body:**
```json
{
  "sections": [
    { "id": 1, "sort_order": 1 },
    { "id": 3, "sort_order": 2 },
    { "id": 2, "sort_order": 3 }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Sections reordered successfully"
}
```

---

## Data Models & Database Schema

### Products Table
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id VARCHAR(50) NOT NULL,
    images JSON NOT NULL, -- Array of image URLs
    sizes JSON NOT NULL, -- Array of available sizes
    colors JSON NOT NULL, -- Array of color objects
    featured BOOLEAN DEFAULT FALSE,
    stock_status ENUM('in_stock', 'out_of_stock', 'pre_order') DEFAULT 'in_stock',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_featured (featured)
);
```

**Colors JSON Structure:**
```json
[
  {
    "name": "Burgundy",
    "hex": "#7C2D4D",
    "image": "products/dress-burgundy.jpg"
  }
]
```

---

### Categories Table
```sql
CREATE TABLE categories (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    parent_id VARCHAR(50) NULL,
    description TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id)
);
```

**Example Data:**
```sql
INSERT INTO categories VALUES
('1', 'Women', 'women', NULL, 'Women\'s fashion collection', 'categories/women.jpg', 1, TRUE),
('1-1', 'Dresses', 'dresses', '1', 'Women\'s dresses', NULL, 1, TRUE),
('1-2', 'Tops', 'tops', '1', 'Women\'s tops', NULL, 2, TRUE),
('3', 'Accessories', 'accessories', NULL, 'Fashion accessories', 'categories/accessories.jpg', 2, TRUE),
('3-1', 'Bags', 'bags', '3', 'Handbags and bags', NULL, 1, TRUE);
```

---

### Orders Table
```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_address TEXT NOT NULL,
    items JSON NOT NULL, -- Array of order items
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_phone (customer_phone)
);
```

**Items JSON Structure:**
```json
[
  {
    "product_id": "1",
    "product_name": "Elegant Silk Dress",
    "size": "M",
    "color": "Burgundy",
    "quantity": 2,
    "price": 189.99,
    "subtotal": 379.98
  }
]
```

---

### Homepage Sections Table
```sql
CREATE TABLE homepage_sections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    section_type VARCHAR(50) NOT NULL, -- 'hero', 'featured_products', 'category_showcase', 'banner', etc.
    title VARCHAR(255),
    subtitle VARCHAR(255),
    description TEXT,
    content JSON, -- Section-specific content
    images JSON, -- Array of image paths
    settings JSON, -- Colors, fonts, layout settings
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (section_type),
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
);
```

---

### Homepage Assets Table
```sql
CREATE TABLE homepage_assets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    type ENUM('image', 'video', 'icon', 'file') NOT NULL,
    name VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    category VARCHAR(50), -- 'hero', 'banner', 'icon', etc.
    metadata JSON, -- Dimensions, file size, mime type, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_type (type)
);
```

---

## API Response Formats

### Success Response
```json
{
  "success": true,
  "data": { ... },
  "message": "Optional success message",
  "meta": { 
    "pagination or other metadata" 
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### HTTP Status Codes
- `200 OK` - Successful GET, PUT, DELETE
- `201 Created` - Successful POST
- `400 Bad Request` - Invalid request
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

---

## Homepage Editor System

The homepage editor allows admins to:

1. **Manage Sections:**
   - Add/Edit/Delete homepage sections
   - Reorder sections via drag & drop
   - Enable/Disable sections
   - Preview changes

2. **Section Types:**
   - `hero`: Hero banner with title, subtitle, image, buttons
   - `featured_products`: Featured products carousel/grid
   - `category_showcase`: Category display with images
   - `banner`: Promotional banner
   - `text_block`: Custom text content
   - `custom`: Custom HTML/component

3. **Asset Management:**
   - Upload images, icons, videos
   - Organize by category
   - View asset library
   - Delete unused assets

4. **Settings Per Section:**
   - Background colors
   - Text colors
   - Layout options (grid, carousel, etc.)
   - Spacing and sizing
   - Animation preferences

---

## Authentication & Authorization

### Admin Routes (Protected)
All `/api/admin/*` endpoints require authentication.

**Recommended Implementation:**
- Laravel Sanctum for SPA authentication
- Or JWT tokens
- Or Laravel session-based auth

**Headers Required:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

### Public Routes
All other endpoints are publicly accessible (no auth required).

---

## File Upload & Storage

### Storage Structure
```
storage/app/public/
├── products/
│   ├── {product-id}/
│   │   ├── main-image.jpg
│   │   ├── color-variant-1.jpg
│   │   └── color-variant-2.jpg
├── categories/
│   ├── women.jpg
│   └── accessories.jpg
├── homepage-assets/
│   ├── hero/
│   │   ├── uuid-123.jpg
│   │   └── uuid-456.jpg
│   ├── banner/
│   └── icon/
```

### File Upload Requirements
- **Max file size:** 10MB
- **Allowed types:** jpg, jpeg, png, gif, webp, svg
- **Image optimization:** Auto-resize and compress
- **Naming:** Use UUIDs to prevent conflicts
- **Public access:** Files must be publicly accessible via URL

### Laravel Storage Configuration
```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

**Run:** `php artisan storage:link`

---

## CORS Configuration

### Required CORS Settings
```php
// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:8080',      // Development
        'http://127.0.0.1:8080',      // Development
        'https://your-domain.com',    // Production
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## Implementation Notes

### 1. URL Slugs
- All slugs must be unique and URL-friendly
- Auto-generate from names: "Elegant Silk Dress" → "elegant-silk-dress"
- Use for SEO-friendly URLs

### 2. Image URLs
- Return absolute URLs in API responses
- Use `asset('storage/path')` or `Storage::url('path')`
- Ensure HTTPS in production

### 3. Pagination
- Use Laravel's built-in pagination
- Default: 12 items per page for products
- Include meta information in response

### 4. Validation Rules

**Product Creation:**
- name: required, string, max:255
- slug: required, unique, alpha_dash
- price: required, numeric, min:0
- images: required, array, min:1
- sizes: required, array, min:1
- colors: required, array, min:1

**Order Creation:**
- customer.name: required, string, max:255
- customer.phone: required, string, max:50
- customer.address: required, string
- items: required, array, min:1
- total: required, numeric, min:0

### 5. Error Handling
- Use try-catch blocks
- Log errors for debugging
- Return user-friendly error messages
- Include validation errors in detail

### 6. Performance Optimization
- Use database indexes
- Implement caching for categories and homepage sections
- Optimize images before storage
- Use eager loading for relationships

### 7. Data Seeding
- Create seeders for initial categories
- Add sample products for testing
- Include at least 2 main categories with subcategories
- Add 10-15 sample products

### 8. Order Number Generation
Format: `ORD-{YEAR}-{SEQUENCE}`
Example: `ORD-2025-00123`

### 9. Multi-language Support
- Frontend handles translations via i18next
- Backend returns English data
- Consider adding `translations` JSON field for future multilingual content

### 10. Testing Requirements
- Create API tests for all endpoints
- Test validation rules
- Test error handling
- Test file uploads

---

## API Testing Examples

### Using cURL

**Get all products:**
```bash
curl -X GET "http://localhost:8000/api/products" \
  -H "Accept: application/json"
```

**Get featured products:**
```bash
curl -X GET "http://localhost:8000/api/products?featured=true" \
  -H "Accept: application/json"
```

**Create order:**
```bash
curl -X POST "http://localhost:8000/api/orders" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "customer": {
      "name": "Jane Doe",
      "phone": "+1234567890",
      "address": "123 Main St"
    },
    "items": [
      {
        "product_id": "1",
        "size": "M",
        "color": "Burgundy",
        "quantity": 2,
        "price": 189.99
      }
    ],
    "total": 379.98
  }'
```

---

## Priority Implementation Order

### Phase 1 (Critical - Before Frontend Build)
1. Products API (GET all, GET by slug)
2. Categories API (GET all, GET by slug)
3. Basic database structure
4. CORS configuration
5. File storage setup

### Phase 2 (High Priority)
1. Orders API (POST)
2. Order management in admin panel
3. Product image upload
4. Featured products filtering

### Phase 3 (Homepage Editor)
1. Homepage sections API (GET)
2. Homepage assets upload API
3. Admin endpoints for section management
4. Section reordering

### Phase 4 (Enhancement)
1. Admin authentication
2. Advanced filtering and search
3. Analytics and reporting
4. Email notifications for orders

---

## Questions for Laravel Team

Please confirm or clarify the following:

1. **Database:** MySQL, PostgreSQL, or other?
2. **Laravel Version:** Which version will you use?
3. **Authentication:** Preference for Sanctum, JWT, or session-based?
4. **File Storage:** Local storage or cloud (S3, DigitalOcean Spaces)?
5. **Admin Panel:** Will you build custom admin or use package (Nova, Filament)?
6. **Order Management:** Email notifications required? SMS integration?
7. **Payment Integration:** Any payment gateway integration needed?
8. **Deployment:** Server environment details?
9. **API Rate Limiting:** Should we implement rate limiting?
10. **Product Inventory:** Do we need stock quantity tracking?

---

## Contact & Collaboration

**Frontend Developer:** (Your contact info)
**Backend Developer:** (Laravel team contact)

**Repository:** (Git repository URL)
**Documentation:** (Additional docs location)
**Design Files:** (Figma/design link if available)

---

## Appendix

### Sample Category Structure
```
Women (id: 1)
├── Dresses (id: 1-1)
├── Tops (id: 1-2)
├── Bottoms (id: 1-3)
└── Outerwear (id: 1-4)

Accessories (id: 3)
├── Bags (id: 3-1)
├── Jewelry (id: 3-2)
└── Scarves (id: 3-3)
```

### Sample Product Entry
```json
{
  "id": "1",
  "name": "Elegant Silk Dress",
  "slug": "elegant-silk-dress",
  "price": 189.99,
  "images": [
    "https://example.com/storage/products/1/main.jpg",
    "https://example.com/storage/products/1/detail-1.jpg"
  ],
  "category_id": "1-1",
  "description": "A luxurious silk dress...",
  "sizes": ["XS", "S", "M", "L", "XL"],
  "colors": [
    {
      "name": "Burgundy",
      "hex": "#7C2D4D",
      "image": "https://example.com/storage/products/1/burgundy.jpg"
    }
  ],
  "featured": true,
  "stock_status": "in_stock"
}
```

---

**Document Version:** 1.0
**Last Updated:** January 15, 2025
**Status:** Ready for Review

---

## Next Steps

1. **Review** this specification with Laravel team
2. **Clarify** any questions or requirements
3. **Agree** on timeline and milestones
4. **Setup** development and staging environments
5. **Implement** Phase 1 endpoints
6. **Test** integration between frontend and backend
7. **Deploy** to production

Good luck with the implementation! 🚀

