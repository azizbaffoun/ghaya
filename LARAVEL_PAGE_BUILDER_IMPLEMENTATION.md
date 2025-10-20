# Laravel Page Builder Implementation Report

## Project Overview
We need to implement a page builder system in Laravel that allows managing homepage banners and sections through an admin interface, with APIs that our React website will consume to display dynamic content.

## Current React Admin Interface Reference
**IMPORTANT**: The Laravel admin interface must match our existing React admin exactly. Here's what it looks like:

### UI Screenshots & Features
- **Two-tab interface**: "Banners" and "Page Sections" tabs
- **Banner management**: Card-based layout with image previews, edit/delete buttons
- **Banner form**: Title, subtitle, image upload with preview, CTA buttons, type dropdown, active toggle
- **Section management**: Toggle visibility switches, status badges
- **Responsive design**: Mobile-friendly with proper touch targets

## Database Schema Required

### 1. Banners Table
```sql
CREATE TABLE banners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    image VARCHAR(500) NOT NULL,
    cta_text VARCHAR(100) NULL,
    cta_link VARCHAR(500) NULL,
    type ENUM('hero', 'promotional', 'category') DEFAULT 'hero',
    is_active BOOLEAN DEFAULT true,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### 2. Homepage Sections Table
```sql
CREATE TABLE homepage_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    title VARCHAR(255) NULL,
    content TEXT NULL,
    settings JSON NULL,
    is_visible BOOLEAN DEFAULT true,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## API Endpoints Required

### Public APIs (for React website)
```
GET /api/banners - Get all active banners
GET /api/homepage-sections - Get all visible sections  
GET /api/homepage - Combined banners + sections
```

### Admin APIs (for Laravel admin panel)
```
GET /api/admin/banners - List all banners (paginated)
POST /api/admin/banners - Create banner
PUT /api/admin/banners/{id} - Update banner
DELETE /api/admin/banners/{id} - Delete banner
POST /api/admin/banners/{id}/upload-image - Upload image

GET /api/admin/homepage-sections - List all sections
POST /api/admin/homepage-sections - Create section
PUT /api/admin/homepage-sections/{id} - Update section
DELETE /api/admin/homepage-sections/{id} - Delete section
PUT /api/admin/homepage-sections/reorder - Reorder sections
```

## Laravel Admin Interface Requirements

### Exact UI Match Required
The Laravel admin must replicate this exact interface:

**Page Layout:**
```
┌─────────────────────────────────────────────────────────┐
│                Website Management                       │
│  Manage your website content and homepage sections     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  [Banners] [Page Sections]                             │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Homepage Banners                    [Add Banner]       │
│                                                         │
│  ┌─────────┐  Summer Collection                        │
│  │  IMG    │  Up to 50% off on all items              │
│  │ 200x200 │  [Hero] [Active] [Edit] [Delete]         │
│  └─────────┘                                           │
└─────────────────────────────────────────────────────────┘
```

**Banner Form Fields:**
- Title (required)
- Subtitle (optional)  
- Image URL/Upload (required) with live preview
- CTA Button Text (optional)
- CTA Button Link (optional)
- Banner Type dropdown: Hero Banner, Promotional Banner, Category Banner
- Active Status toggle with description "Show this banner on the website"

**Section Management:**
- List with name, title, description
- Visibility badge (Visible/Hidden)
- Toggle switch for each section

### Design Requirements
- **Framework**: Bootstrap 5 or Tailwind CSS
- **Colors**: Primary Blue (#3B82F6), Success Green (#10B981), Danger Red (#EF4444)
- **Components**: Cards, badges, switches, modals, toast notifications
- **Responsive**: Mobile-friendly design
- **Icons**: Font Awesome or similar

## Sample Data for Testing

### Banners Seeder
```php
[
    'title' => 'Summer Collection',
    'subtitle' => 'Up to 50% off on all items',
    'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&q=80',
    'cta_text' => 'Shop Now',
    'cta_link' => '/products',
    'type' => 'hero',
    'is_active' => true,
    'sort_order' => 1
]
```

### Sections Seeder
```php
[
    'name' => 'Featured Products',
    'type' => 'featured_products',
    'title' => 'Our Best Sellers',
    'is_visible' => true,
    'settings' => ['products_count' => 8, 'show_prices' => true],
    'sort_order' => 1
]
```

## API Response Format

All APIs must return this format:
```json
{
    "success": true,
    "message": "Success message",
    "data": { ... },
    "pagination": { ... } // Only for paginated endpoints
}
```

## Image Upload Requirements

- Store images in `storage/app/public/banners/`
- Return full URLs in API responses
- Support JPG, PNG, WebP formats
- Max file size: 10MB
- Auto-resize to 1200x600 for banners

## CORS Configuration

Configure CORS to allow our React development server:
```php
// config/cors.php
'allowed_origins' => [
    'http://localhost:5173', // React dev server
    'https://yourdomain.com' // Production
]
```

## Files to Create

### Models
- `app/Models/Banner.php`
- `app/Models/HomepageSection.php`

### Controllers
- `app/Http/Controllers/Api/BannerController.php`
- `app/Http/Controllers/Api/HomepageSectionController.php`
- `app/Http/Controllers/Admin/BannerController.php`
- `app/Http/Controllers/Admin/HomepageSectionController.php`

### Requests
- `app/Http/Requests/BannerRequest.php`
- `app/Http/Requests/HomepageSectionRequest.php`

### Views
- `resources/views/admin/banners/index.blade.php`
- `resources/views/admin/banners/form.blade.php`
- `resources/views/admin/sections/index.blade.php`

### Migrations
- `database/migrations/xxxx_create_banners_table.php`
- `database/migrations/xxxx_create_homepage_sections_table.php`

### Seeders
- `database/seeders/BannerSeeder.php`
- `database/seeders/HomepageSectionSeeder.php`

## Testing Checklist

Once implemented, please test:
- [ ] Banner CRUD operations work
- [ ] Section visibility toggles work
- [ ] Image uploads work and return correct URLs
- [ ] API responses match the required format
- [ ] Admin UI matches the React interface exactly
- [ ] CORS allows React dev server access
- [ ] All endpoints return proper error messages

## Next Steps After Laravel Implementation

1. **Laravel team** implements the above requirements
2. **React team** will:
   - Create API service layer
   - Update Home page to fetch from Laravel APIs
   - Run `npm run build`
   - Provide build files for Laravel integration

## Contact

If you have questions about the UI requirements or need clarification on any features, please refer to the attached React admin screenshots and ask for clarification.

**Priority**: The admin interface must match the React version exactly - this is critical for user experience consistency.

---

## Detailed API Specifications

### GET /api/banners
**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Summer Collection",
            "subtitle": "Up to 50% off on all items",
            "image": "https://yourdomain.com/storage/banners/summer.jpg",
            "cta_text": "Shop Now",
            "cta_link": "/products",
            "type": "hero",
            "sort_order": 1
        }
    ]
}
```

### GET /api/homepage-sections
**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Featured Products",
            "type": "featured_products",
            "title": "Our Best Sellers",
            "is_visible": true,
            "settings": {
                "products_count": 8,
                "show_prices": true
            },
            "sort_order": 1
        }
    ]
}
```

### POST /api/admin/banners
**Request:**
```json
{
    "title": "New Banner",
    "subtitle": "Banner subtitle",
    "image": "https://example.com/image.jpg",
    "cta_text": "Click Here",
    "cta_link": "/category/new",
    "type": "hero",
    "is_active": true
}
```

**Response:**
```json
{
    "success": true,
    "message": "Banner created successfully",
    "data": {
        "id": 2,
        "title": "New Banner",
        "subtitle": "Banner subtitle",
        "image": "https://example.com/image.jpg",
        "cta_text": "Click Here",
        "cta_link": "/category/new",
        "type": "hero",
        "is_active": true,
        "sort_order": 2,
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

### Error Response Format
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title field is required."],
        "image": ["The image field is required."]
    }
}
```

## Laravel Routes

### API Routes (api.php)
```php
// Public routes
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/homepage-sections', [HomepageSectionController::class, 'index']);
Route::get('/homepage', [HomepageController::class, 'index']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::apiResource('banners', AdminBannerController::class);
    Route::post('banners/{banner}/upload-image', [AdminBannerController::class, 'uploadImage']);
    
    Route::apiResource('homepage-sections', AdminHomepageSectionController::class);
    Route::put('homepage-sections/reorder', [AdminHomepageSectionController::class, 'reorder']);
});
```

### Web Routes (web.php)
```php
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/website', [WebsiteController::class, 'index'])->name('admin.website');
    Route::resource('banners', BannerController::class);
    Route::resource('sections', HomepageSectionController::class);
});
```

## Model Examples

### Banner Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'cta_text',
        'cta_link',
        'type',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
```

### HomepageSection Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'title',
        'content',
        'settings',
        'is_visible',
        'sort_order'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'settings' => 'array',
        'sort_order' => 'integer'
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
```

## Validation Rules

### BannerRequest
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string|max:500',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:500',
            'type' => 'required|in:hero,promotional,category',
            'is_active' => 'boolean'
        ];
    }
}
```

### HomepageSectionRequest
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomepageSectionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'settings' => 'nullable|array',
            'is_visible' => 'boolean'
        ];
    }
}
```

This completes the comprehensive implementation guide for the Laravel team.
