# Store Management Database Schema

## 🗄️ Additional Database Tables for Store Management

### 1. **Store Settings Table**
```sql
CREATE TABLE store_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    store_name VARCHAR(255) NOT NULL,
    store_description TEXT,
    store_email VARCHAR(255),
    store_phone VARCHAR(50),
    store_address TEXT,
    store_city VARCHAR(100),
    store_state VARCHAR(100),
    store_postal_code VARCHAR(20),
    store_country VARCHAR(100) DEFAULT 'Morocco',
    business_hours JSON,
    store_status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    currency VARCHAR(3) DEFAULT 'MAD',
    currency_symbol VARCHAR(10) DEFAULT 'د.م',
    timezone VARCHAR(50) DEFAULT 'Africa/Casablanca',
    language VARCHAR(5) DEFAULT 'fr',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 2. **Store Branding Table**
```sql
CREATE TABLE store_branding (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    logo_path VARCHAR(500),
    favicon_path VARCHAR(500),
    hero_banner_path VARCHAR(500),
    primary_color VARCHAR(7) DEFAULT '#3B82F6',
    secondary_color VARCHAR(7) DEFAULT '#10B981',
    accent_color VARCHAR(7) DEFAULT '#F59E0B',
    font_family VARCHAR(100) DEFAULT 'Inter',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 3. **Store Content Table**
```sql
CREATE TABLE store_content (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_type ENUM('homepage', 'about', 'terms', 'privacy', 'returns', 'shipping') NOT NULL,
    title VARCHAR(255),
    content LONGTEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_page_type (page_type)
);
```

### 4. **Store Banners Table**
```sql
CREATE TABLE store_banners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    banner_type ENUM('hero', 'promotional', 'category', 'footer') NOT NULL,
    position INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    link_url VARCHAR(500),
    link_text VARCHAR(100),
    start_date DATETIME,
    end_date DATETIME,
    target_audience ENUM('all', 'new_customers', 'returning_customers') DEFAULT 'all',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 5. **Store Images Table**
```sql
CREATE TABLE store_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    caption TEXT,
    image_type ENUM('gallery', 'testimonial', 'team', 'achievement', 'partner') NOT NULL,
    category VARCHAR(100),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    file_size INT,
    dimensions VARCHAR(20),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 6. **Stock Management Table**
```sql
CREATE TABLE stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    product_variant_id BIGINT UNSIGNED NULL,
    movement_type ENUM('in', 'out', 'adjustment', 'transfer') NOT NULL,
    quantity INT NOT NULL,
    previous_stock INT NOT NULL,
    new_stock INT NOT NULL,
    reason VARCHAR(255),
    reference_type ENUM('order', 'purchase', 'return', 'damage', 'adjustment', 'transfer') NULL,
    reference_id BIGINT UNSIGNED NULL,
    notes TEXT,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### 7. **Stock Alerts Table**
```sql
CREATE TABLE stock_alerts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    product_variant_id BIGINT UNSIGNED NULL,
    alert_type ENUM('low_stock', 'out_of_stock', 'reorder_point') NOT NULL,
    current_stock INT NOT NULL,
    threshold_stock INT NOT NULL,
    is_resolved BOOLEAN DEFAULT FALSE,
    resolved_at TIMESTAMP NULL,
    resolved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### 8. **Store Analytics Table**
```sql
CREATE TABLE store_analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    metric_type ENUM('page_views', 'unique_visitors', 'orders', 'revenue', 'conversions') NOT NULL,
    metric_value DECIMAL(15,2) NOT NULL,
    additional_data JSON,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_date_metric (date, metric_type)
);
```

### 9. **Landing Page Sections Table**
```sql
CREATE TABLE landing_page_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_type ENUM('hero', 'products', 'categories', 'about', 'testimonials', 'contact') NOT NULL,
    title VARCHAR(255),
    content LONGTEXT,
    settings JSON,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);
```

### 10. **Store SEO Table**
```sql
CREATE TABLE store_seo (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_type ENUM('homepage', 'category', 'product', 'custom') NOT NULL,
    page_identifier VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    og_title VARCHAR(255),
    og_description TEXT,
    og_image VARCHAR(500),
    twitter_title VARCHAR(255),
    twitter_description TEXT,
    twitter_image VARCHAR(500),
    canonical_url VARCHAR(500),
    robots_meta VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_page (page_type, page_identifier)
);
```

## 🔄 Updated Product Variants Table

### Add Stock Management Fields to Product Variants:
```sql
ALTER TABLE product_variants 
ADD COLUMN reorder_point INT DEFAULT 10,
ADD COLUMN max_stock INT DEFAULT 1000,
ADD COLUMN reserved_stock INT DEFAULT 0,
ADD COLUMN cost_price DECIMAL(10,2) NULL,
ADD COLUMN supplier VARCHAR(255) NULL,
ADD COLUMN supplier_sku VARCHAR(255) NULL;
```

## 🔄 Updated Products Table

### Add Stock Management Fields to Products:
```sql
ALTER TABLE products 
ADD COLUMN track_inventory BOOLEAN DEFAULT TRUE,
ADD COLUMN allow_backorder BOOLEAN DEFAULT FALSE,
ADD COLUMN stock_status ENUM('in_stock', 'out_of_stock', 'on_backorder') DEFAULT 'in_stock',
ADD COLUMN total_stock INT DEFAULT 0,
ADD COLUMN reserved_stock INT DEFAULT 0,
ADD COLUMN available_stock INT DEFAULT 0;
```

## 📊 Database Relationships

### Store Management Relationships:
- `store_settings` → One-to-one with store branding
- `store_banners` → Many-to-one with categories (optional)
- `store_images` → Categorized by image_type
- `stock_movements` → Many-to-one with products and product_variants
- `stock_alerts` → Many-to-one with products and product_variants
- `store_analytics` → Daily metrics tracking
- `landing_page_sections` → Configurable homepage sections
- `store_seo` → SEO data for different page types

## 🎯 Key Features Supported

### Store Management:
- ✅ Store information and branding
- ✅ Logo and favicon management
- ✅ Banner management with scheduling
- ✅ Content management system
- ✅ SEO optimization
- ✅ Multi-language support

### Stock Management:
- ✅ Real-time stock tracking
- ✅ Stock movement history
- ✅ Low stock alerts
- ✅ Reorder point management
- ✅ Stock adjustments and transfers
- ✅ Reserved stock tracking

### Analytics & Reporting:
- ✅ Daily analytics tracking
- ✅ Performance metrics
- ✅ Sales and inventory reports
- ✅ Customer behavior analytics

### Landing Page Builder:
- ✅ Drag & drop section builder
- ✅ Multiple section types
- ✅ Real-time preview
- ✅ Mobile responsive design

This database schema provides comprehensive support for all store management features including branding, content management, stock tracking, and analytics.

