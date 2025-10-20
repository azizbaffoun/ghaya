# Yakine Mode - Admin Dashboard PRD (Product Requirements Document)

## 📋 Executive Summary

The Yakine Mode Admin Dashboard is a comprehensive e-commerce management platform designed for efficient store operations. It provides a modern, multilingual (French/Arabic) interface with RTL/LTR support, enabling administrators to manage products, orders, customers, and delivery operations seamlessly.

## 🎯 Product Overview

### Vision
Create an intuitive, powerful admin dashboard that streamlines e-commerce operations for Yakine Mode, supporting both French and Arabic languages with complete RTL/LTR functionality.

### Target Users
- **Primary**: Store administrators and managers
- **Secondary**: Store workers and staff
- **Tertiary**: System administrators

### Key Success Metrics
- Reduced order processing time by 60%
- Improved inventory management efficiency
- Enhanced customer service capabilities
- Seamless multilingual experience
- 99.9% system uptime
- < 2 second page load times
- 95% user satisfaction score

## 🎨 Frontend Architecture

### Technology Stack
- **Framework**: React with TypeScript
- **Styling**: Tailwind CSS with custom RTL support
- **State Management**: Redux Toolkit
- **UI Components**: Custom component library
- **Icons**: Heroicons or similar icon library
- **Charts**: Chart.js or Recharts for analytics
- **Forms**: React Hook Form with validation
- **Routing**: React Router for navigation
- **Responsive**: Mobile-first design approach
- **Animation**: Framer Motion for smooth transitions
- **Date Handling**: Day.js for date manipulation
- **Validation**: Yup or Zod for form validation

### Frontend Features
1. **Multilingual Support** (French/Arabic with RTL/LTR)
2. **Responsive Design** (Desktop/Tablet/Mobile)
3. **Real-time Updates** (Live order tracking)
4. **Role-based UI** (Admin/Worker interface variations)
5. **Advanced Search & Filtering**
6. **Bulk Operations** (Mass actions)
7. **Export/Import UI** (Data export interface)
8. **Delivery Integration UI** (First Delivery service interface)
9. **Activity Tracking UI** (User action display)
10. **Data Management UI** (Data handling interface)
11. **Intuitive UX Flow** (Optimized user experience)
12. **Progressive Enhancement** (Mobile-first approach)

## 📱 User Interface & Experience

### Design Principles
- **Clean & Modern**: Minimalist design with clear visual hierarchy
- **Intuitive Navigation**: Logical menu structure with breadcrumbs
- **Consistent Branding**: Yakine Mode brand colors and typography
- **Accessibility**: WCAG 2.1 compliant with keyboard navigation
- **Performance**: Fast loading with smooth animations
- **User-Centric**: Designed around actual user workflows
- **Progressive Disclosure**: Show information when needed
- **Consistent Interactions**: Predictable user interface patterns
- **Visual Feedback**: Clear response to user actions
- **Error Prevention**: Prevent mistakes before they happen

### Color Scheme
- **Primary**: Professional blue (#3B82F6)
- **Secondary**: Success green (#10B981)
- **Warning**: Orange (#F59E0B)
- **Danger**: Red (#EF4444)
- **Neutral**: Gray scale for text and backgrounds
- **Gradient**: Blue to purple gradients for CTAs

### Typography
- **French**: Inter, Roboto, system fonts
- **Arabic**: Cairo, Amiri, Noto Sans Arabic
- **RTL Support**: Automatic text direction and alignment

## 🧭 Navigation Structure

### Main Sidebar Navigation
```
📊 Dashboard
📦 Products
📁 Categories
🛒 Orders
   ├── All Orders
   ├── New Orders
   ├── Confirmed
   ├── Ready for Pickup
   └── In Delivery
👥 Customers
🌐 Website Management
   ├── Page Builder
   └── Banners
🚚 Delivery
   └── Settings
⚙️ Settings
   ├── General Settings
   ├── System Settings
   ├── User Management
   └── Languages
👤 Profile
```

### Top Navigation Bar
- **Hamburger Menu** (Mobile)
- **Page Title** with breadcrumbs
- **Language Switcher** (French/Arabic)
- **User Profile** with dropdown
- **Notifications** (Real-time alerts)
- **Search Bar** (Global search)
- **Help & Support** (Documentation link)

## 📄 Page Specifications

### 1. Dashboard Home Page

#### Purpose
Central command center providing intuitive overview of store operations with actionable insights and quick access to key functions.

#### UX-Focused Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                WELCOME & QUICK ACTIONS                  │
│  [Logo] Welcome back, [Name] | [Quick Stats] [Alerts]  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                KEY METRICS CARDS                        │
│  📦 Products  🛒 Orders  💰 Revenue  👥 Customers      │
│    150        45        $12,450      23                │
│  [View All]  [View All] [View Report] [View All]       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                QUICK ACTIONS PANEL                      │
│  [Add Product] [New Order] [Print Labels] [Sync FD]    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                SMART FILTERS & SEARCH                   │
│  [🔍 Search] [📅 Today] [⚠️ Alerts] [📊 Reports]        │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                RECENT ORDERS (PRIORITY)                 │
│  Order# | Customer | Status | Amount | [Quick Actions]  │
│  YM001  | John Doe | Pending| $125   | [Call][Confirm]  │
│  YM002  | Jane S.  | Ready  | $89    | [Print][Pickup]  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                SYSTEM ALERTS & NOTIFICATIONS            │
│  ⚠️ Low Stock (5 items) | 📦 Ready for Pickup (3)      │
└─────────────────────────────────────────────────────────┘
```

#### Key Metrics Cards
1. **Total Products**: Active products count
2. **Active Orders**: Orders in progress
3. **Pending Orders**: Orders awaiting confirmation
4. **Total Revenue**: Today's and monthly revenue
5. **New Customers**: Today's and monthly new customers
6. **Low Stock Alerts**: Products with low inventory
7. **Ready for Pickup**: Orders ready for pickup
8. **In Delivery**: Orders currently being delivered

#### Recent Orders Table
**Desktop View:**
- Order Number (clickable)
- Customer First Name
- Customer Last Name
- Address (truncated)
- Phone Number
- Order Status Badge
- Order Date
- Total Amount
- Actions (View/Edit)

**Mobile View:**
- Card-based layout
- Essential information only
- Touch-friendly buttons
- Swipe gestures

#### UX Features
- **Real-time Updates**: Auto-refresh every 30 seconds
- **Quick Actions**: Click order number to view details
- **Status Filtering**: Filter by order status
- **Search**: Real-time search across orders
- **Pagination**: 10/25/50 orders per page
- **Export**: Export recent orders to CSV
- **Smart Notifications**: Contextual alerts and reminders
- **Keyboard Shortcuts**: Power user efficiency
- **Drag & Drop**: Intuitive reordering
- **Bulk Operations**: Multi-select with batch actions
- **Quick Preview**: Hover to see order details
- **One-Click Actions**: Common tasks in single click

### 2. Products Management

#### Purpose
Intuitive product catalog management with streamlined workflows and visual product organization.

#### UX-Optimized Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                PRODUCTS HEADER                          │
│  📦 Products (150) | [➕ Add Product] [📊 Analytics]     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                SMART SEARCH & FILTERS                   │
│  [🔍 Search products...] [📁 Category] [📊 Status] [⚡]  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                VIEW OPTIONS & BULK ACTIONS              │
│  [Grid View] [List View] | [Select All] [Bulk Actions] │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                PRODUCTS GRID/TABLE                      │
│  [IMG] Product Name    | Category | Price | Stock | [⚡]│
│  [IMG] T-Shirt Blue    | Clothing | $25   | 150   | [⚡]│
│  [IMG] Jeans Black     | Clothing | $45   | 75    | [⚡]│
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                QUICK ACTIONS FAB (MOBILE)               │
│                    [➕ Add Product]                      │
└─────────────────────────────────────────────────────────┘
```

#### Product List View
**Table Columns:**
- Product (Image + Name + SKU)
- Category
- Price (Regular + Compare price)
- Stock Status (In Stock/Out of Stock/Low Stock)
- Status (Active/Inactive)
- Actions (Edit/Delete)

**Filters:**
- Search by name, SKU, description
- Category dropdown
- Status filter (Active/Inactive)
- Stock status filter

**Bulk Actions:**
- Select multiple products
- Activate/Deactivate
- Delete selected
- Export to CSV

#### Product Create/Edit Form
**Multi-step Form Sections:**

1. **Basic Information**
   - Product name (required)
   - Slug (auto-generated, editable)
   - Description (rich text editor)
   - Short description
   - SKU (required, unique)

2. **Pricing & Inventory**
   - Regular price (required)
   - Compare price (optional)
   - Cost price (internal)
   - Stock quantity
   - Track inventory (checkbox)
   - Low stock threshold

3. **Categories & Settings**
   - Category selection (dropdown)
   - Featured product (toggle)
   - Active status (toggle)
   - Weight (for shipping)

4. **Images**
   - Multiple image upload
   - Drag & drop reordering
   - Set primary image
   - Image alt text
   - Image compression

5. **Variants** (Future feature)
   - Size selection
   - Color selection
   - Variant SKU
   - Variant price
   - Variant stock

6. **SEO Settings**
   - Meta title
   - Meta description
   - Meta keywords

#### UX Features
- **Real-time Validation**: Instant feedback on form errors
- **Auto-save Draft**: Save progress automatically
- **Image Upload**: Drag & drop with preview
- **Slug Generator**: Auto-generate from product name
- **Mobile Responsive**: Touch-friendly forms

### 3. Categories Management

#### Purpose
Hierarchical category organization for product catalog.

#### Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                CATEGORIES HEADER                        │
│  Categories | [Add Category Button]                     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                CATEGORIES TREE                          │
│  📁 Clothing                                           │
│    ├── 📁 T-Shirts                                    │
│    ├── 📁 Jeans                                       │
│    └── 📁 Jackets                                     │
│  📁 Accessories                                        │
│    ├── 📁 Bags                                        │
│    └── 📁 Shoes                                       │
└─────────────────────────────────────────────────────────┘
```

#### Category List View
**Tree Structure:**
- Hierarchical display with indentation
- Expandable/collapsible categories
- Drag & drop reordering
- Product count per category
- Status indicators

**Category Information:**
- Category name
- Slug
- Product count
- Status badge
- Sort order
- Created date
- Actions (Edit/Delete/Add Subcategory)

#### Category Create/Edit Form
**Form Fields:**
- Category name (required)
- Slug (auto-generated, editable)
- Description (textarea)
- Parent category (dropdown)
- Category image (file upload)
- Active status (toggle)
- Sort order (number)
- Meta title
- Meta description

#### UX Features
- **Tree Navigation**: Visual hierarchy
- **Drag & Drop**: Reorder categories
- **Search**: Find categories quickly
- **Bulk Actions**: Mass operations
- **Image Upload**: Category images with preview

### 4. Orders Management

#### Purpose
Complete order lifecycle management with intuitive status tracking and workflow optimization.

#### Order Management UX Flow
```
📋 ORDER WORKFLOW
┌─────────────────────────────────────────────────────────┐
│  NEW ORDERS → CONFIRMED → READY PICKUP → IN DELIVERY    │
│      ↓            ↓            ↓            ↓          │
│   [Call]      [Print]      [Request]    [Track]        │
│  Customer     Labels       Pickup      Delivery        │
└─────────────────────────────────────────────────────────┘
```

#### Order Subpages Structure
```
🛒 ORDERS DASHBOARD
├── 📊 Overview (All Orders)
├── 🆕 New Orders (Pending Confirmation)
├── ✅ Confirmed (Ready for Processing)
├── 📦 Ready for Pickup (Printed & Ready)
├── 🚚 In Delivery (Being Delivered)
└── 📋 Order Details (Individual Order View)
```

#### 4.1 Orders Overview Dashboard
**Purpose**: Central hub for all order management activities

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────┐
│                ORDERS OVERVIEW                          │
│  [Quick Stats] [Recent Activity] [Alerts]              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                ORDER FILTERS & SEARCH                   │
│  [Search] [Status] [Date] [Customer] [Quick Filters]   │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                ORDERS TABLE                             │
│  [Select] Order# | Customer | Total | Status | Actions │
│  [  ✓  ] YM001  | John Doe | $125  | Pending| [View]  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                BULK ACTIONS BAR                         │
│  [Update Status] [Export] [Print] [Send to Delivery]   │
└─────────────────────────────────────────────────────────┘
```

**Key Features:**
- **Quick Stats Cards**: Total orders, pending, confirmed, in delivery
- **Smart Filters**: One-click status filters
- **Bulk Selection**: Checkbox selection with bulk actions
- **Real-time Updates**: Live order status changes
- **Quick Actions**: Hover actions for common tasks

#### 4.2 New Orders Page
**Purpose**: Handle orders awaiting customer confirmation

**UX Flow:**
1. **Order List**: Show pending orders with customer details
2. **Call Customer**: One-click phone call integration
3. **Confirm Order**: Mark as confirmed after customer confirmation
4. **Decline Order**: Mark as declined with reason
5. **Add Notes**: Internal notes about customer interaction

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────┐
│                NEW ORDERS (PENDING)                     │
│  📞 Call customers to confirm orders                    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Order# | Customer | Phone | Amount | Actions          │
│  YM001  | John Doe | 555   | $125   | [Call][Confirm]  │
│  YM002  | Jane S.  | 444   | $89    | [Call][Confirm]  │
└─────────────────────────────────────────────────────────┘
```

**UX Features:**
- **Priority Sorting**: Orders by urgency/amount
- **Customer Info**: Full contact details visible
- **Call Integration**: Click to call functionality
- **Quick Confirm**: One-click confirmation
- **Bulk Actions**: Confirm multiple orders
- **Notes Panel**: Add customer interaction notes

#### 4.3 Confirmed Orders Page
**Purpose**: Process confirmed orders for printing and pickup

**UX Flow:**
1. **Order List**: Show confirmed orders ready for processing
2. **Print Labels**: Generate shipping labels
3. **Mark Printed**: Update order status
4. **Request Pickup**: Send to delivery service
5. **Track Progress**: Monitor order status

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────┐
│                CONFIRMED ORDERS                         │
│  📄 Print shipping labels and prepare for pickup       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Order# | Customer | Items | Print | Status | Actions  │
│  YM001  | John Doe |   3   | [Print]| Ready | [Pickup] │
│  YM002  | Jane S.  |   2   | [Print]| Ready | [Pickup] │
└─────────────────────────────────────────────────────────┘
```

**UX Features:**
- **Print Queue**: Batch printing capabilities
- **Label Preview**: See labels before printing
- **Bulk Print**: Print multiple orders at once
- **Status Tracking**: Visual progress indicators
- **Pickup Request**: One-click delivery request

#### 4.4 Ready for Pickup Page
**Purpose**: Manage orders ready for delivery pickup

**UX Flow:**
1. **Order List**: Show printed orders ready for pickup
2. **Request Pickup**: Send pickup request to delivery service
3. **Track Pickup**: Monitor pickup status
4. **Update Status**: Mark as picked up
5. **Handle Issues**: Manage pickup problems

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────┐
│                READY FOR PICKUP                         │
│  🚚 Request pickup from delivery service                │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Order# | Customer | Printed | Pickup | Status | Actions│
│  YM001  | John Doe |  Today  | [Request]| Ready | [Track]│
│  YM002  | Jane S.  |  Today  | [Request]| Ready | [Track]│
└─────────────────────────────────────────────────────────┘
```

**UX Features:**
- **Pickup Scheduling**: Schedule pickup times
- **Bulk Pickup**: Request pickup for multiple orders
- **Status Updates**: Real-time pickup status
- **Issue Handling**: Report pickup problems
- **Delivery Tracking**: Track package progress

#### 4.5 In Delivery Page
**Purpose**: Track orders currently being delivered

**UX Flow:**
1. **Order List**: Show orders in transit
2. **Track Delivery**: Monitor delivery progress
3. **Update Status**: Mark as delivered
4. **Handle Issues**: Manage delivery problems
5. **Customer Communication**: Send updates to customers

**Layout Structure:**
```
┌─────────────────────────────────────────────────────────┐
│                IN DELIVERY                              │
│  📦 Track orders currently being delivered              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  Order# | Customer | Tracking | Status | ETA | Actions │
│  YM001  | John Doe |  FD123   | Transit| 2h  | [Track] │
│  YM002  | Jane S.  |  FD124   | Transit| 1h  | [Track] │
└─────────────────────────────────────────────────────────┘
```

**UX Features:**
- **Real-time Tracking**: Live delivery updates
- **ETA Display**: Estimated delivery times
- **Status Updates**: Automatic status changes
- **Customer Notifications**: Send tracking info
- **Issue Resolution**: Handle delivery problems

#### 4.6 Order Details Page
**Purpose**: Comprehensive view and management of individual orders

**Tabbed Interface:**
1. **Overview Tab**: Order summary and status
2. **Items Tab**: Product details and quantities
3. **Customer Tab**: Customer information and history
4. **Timeline Tab**: Order history and updates
5. **Actions Tab**: Available actions and tools

**UX Features:**
- **Quick Actions**: Common tasks easily accessible
- **Status Updates**: One-click status changes
- **Communication**: Direct customer contact
- **Document Generation**: Print invoices, labels
- **Notes Management**: Internal and customer notes

#### Order Details View
**Tabbed Interface:**

1. **Overview Tab**
   - Order number and date
   - Current status and payment status
   - Total amount breakdown
   - Payment method
   - Delivery method

2. **Items Tab**
   - Product details with images
   - Quantities and prices
   - Variant information
   - Item status

3. **Customer Tab**
   - Customer information
   - Shipping address
   - Billing address
   - Order history

4. **Timeline Tab**
   - Status change history
   - Payment history
   - Delivery updates
   - Admin notes

5. **Actions Tab**
   - Update status
   - Add tracking number
   - Send to delivery API
   - Print documents
   - Send customer email

#### Order Statuses
**Order Statuses:**
- Pending (Order received, awaiting payment)
- Confirmed (Payment received, order confirmed)
- Processing (Order being prepared)
- Shipped (Order sent to delivery service)
- Delivered (Successfully delivered)
- Cancelled (Order cancelled)
- Refunded (Order refunded)

**Payment Statuses:**
- Pending (Awaiting payment)
- Paid (Payment received)
- Failed (Payment failed)
- Refunded (Payment refunded)
- Partially Refunded (Partial refund)

**Delivery Statuses:**
- Not Sent (Not sent to delivery API)
- Sent (Sent to delivery service)
- Picked Up (Package picked up)
- In Transit (On the way to customer)
- Delivered (Successfully delivered)
- Failed (Delivery failed)
- Returned (Package returned)

### 5. Customers Management

#### Purpose
Customer relationship management and order history tracking.

#### Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│                CUSTOMERS HEADER                         │
│  Customers | [Add Customer Button]                      │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                CUSTOMER FILTERS                         │
│  [Search] [Date] [Orders] [Location] [Filter]          │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                CUSTOMERS TABLE                          │
│  Name | Email | Phone | Orders | Total | Last Order    │
│  John | john@ | 555   |   5    | $500  | 1/1/2024     │
└─────────────────────────────────────────────────────────┘
```

#### Customer List View
**Table Columns:**
- Customer Name
- Email Address
- Phone Number
- City/Location
- Total Orders
- Total Spent
- Last Order Date
- Registration Date
- Actions (View/Edit/Orders)

**Advanced Filters:**
- Registration date range
- Order count filter
- Location filter
- Customer status
- Search by name, email, phone

**Customer Segments:**
- VIP customers (high value)
- Regular customers
- New customers
- Inactive customers

#### Customer Details View
**Tabbed Interface:**

1. **Profile Tab**
   - Personal details
   - Contact information
   - Registration date
   - Customer status
   - Profile picture

2. **Orders Tab**
   - Complete order history
   - Order details and status
   - Total spent and average order value
   - Favorite products

3. **Addresses Tab**
   - Default shipping address
   - Additional addresses
   - Address validation

4. **Communication Tab**
   - Email history
   - SMS history
   - Support tickets
   - Marketing preferences

5. **Notes Tab**
   - Internal notes
   - Customer service notes
   - Special instructions
   - VIP status

### 6. Website Management

#### Purpose
Content management and visual customization of the store.

#### Page Builder
**Drag & Drop Interface:**
- Visual page builder
- Section management
- Real-time preview
- Mobile preview
- Template library

**Page Sections:**
1. **Hero Section**
   - Main headline
   - Subheadline
   - Call-to-action button
   - Background image/video
   - Overlay settings

2. **Featured Products**
   - Product selection
   - Display layout (grid/list)
   - Number of products
   - Auto-rotate settings

3. **Categories Showcase**
   - Category selection
   - Display style
   - Images and descriptions
   - Link to category pages

4. **About Section**
   - Company story
   - Mission statement
   - Team photos
   - Achievements/statistics

5. **Testimonials**
   - Customer reviews
   - Star ratings
   - Customer photos
   - Auto-rotation

6. **Contact Information**
   - Contact details
   - Map integration
   - Contact form
   - Social media links

#### Banner Management
**Banner Types:**
1. **Homepage Banners**
   - Hero banner (main homepage banner)
   - Secondary banners
   - Promotional banners
   - Seasonal banners

2. **Category Banners**
   - Category-specific banners
   - Subcategory banners
   - Product collection banners

3. **Promotional Banners**
   - Sale banners
   - New arrival banners
   - Special offer banners
   - Holiday banners

4. **Footer Banners**
   - Newsletter signup
   - Social media promotion
   - Partner logos
   - Trust badges

**Banner Features:**
- Image gallery
- Drag & drop upload
- Image editor (crop, resize, filters)
- Banner positioning
- Responsive preview
- Multiple formats (JPG, PNG, WebP, SVG)
- Automatic optimization
- Alt text management

### 7. Delivery Management

#### Purpose
Integration with First Delivery API for automated shipping management.

#### Delivery Dashboard
**Overview:**
- API connection status
- Token management
- Delivery statistics
- Recent activity
- Error logs

#### API Configuration
**Configuration Fields:**
- API Token (required, masked input)
- API Base URL
- Delivery Service Name
- Default Delivery Type
- Webhook URL (for status updates)
- Test Mode (toggle)

**Features:**
- Secure token input
- Connection testing
- Status indicators
- API logs viewer
- Backup tokens

#### Order Delivery Tracking
**Delivery Statuses:**
- Pending (order confirmed, not sent to delivery API)
- Sent (sent to delivery API, awaiting pickup)
- Picked Up (delivery service has the package)
- In Transit (package is on the way)
- Delivered (successfully delivered)
- Failed (delivery failed, needs attention)
- Returned (package returned to sender)

**Features:**
- Order list with delivery status
- Real-time status updates
- Bulk send to delivery API
- Delivery history timeline
- Error handling and retry
- Customer notifications

#### Automatic Integration
**Workflow:**
1. Order confirmation triggers delivery request
2. API call to First Delivery
3. Response handling and status update
4. Webhook processing for status updates
5. Customer notification with tracking info

**Features:**
- Automatic triggering
- Error handling and retry
- Status synchronization
- Customer updates
- Admin alerts for issues

### 8. Settings & Configuration

#### General Settings
**Store Information:**
- Store name and description
- Contact information
- Address and location
- Business hours
- Logo and branding

**Currency & Pricing:**
- Default currency (MAD)
- Currency symbol position
- Decimal places
- Price display format

**Tax Settings:**
- Tax rates by region
- Tax calculation method
- Tax-inclusive pricing
- Tax exemptions

**Shipping Settings:**
- Shipping zones
- Shipping rates
- Free shipping threshold
- Delivery timeframes

#### System Settings
**Email Configuration:**
- SMTP settings
- Email templates
- Email queue management
- Email testing

**Notification Settings:**
- Order notifications
- Low stock alerts
- System alerts
- Customer notifications

**Security Settings:**
- Password policies
- Session timeout
- Two-factor authentication
- IP restrictions

**Backup & Maintenance:**
- Database backup
- File backup
- Cleanup tasks
- Performance monitoring

#### User Management
**User Roles:**
- Admin (full access)
- Worker (limited access)
- Custom roles (future feature)

**User Management Features:**
- User list with information
- Role assignment
- Permission matrix
- Activity logs
- Password reset

## 📊 Data Models & Types

### TypeScript Interfaces
**User Interface:**
```typescript
interface User {
  id: string;
  name: string;
  email: string;
  role: 'admin' | 'worker';
  avatar?: string;
  lastLogin?: Date;
}
```

**Product Interface:**
```typescript
interface Product {
  id: string;
  name: string;
  slug: string;
  description: string;
  shortDescription: string;
  sku: string;
  price: number;
  comparePrice?: number;
  costPrice?: number;
  stockQuantity: number;
  stockStatus: 'in_stock' | 'out_of_stock' | 'low_stock';
  isActive: boolean;
  isFeatured: boolean;
  weight?: number;
  categoryId: string;
  images: ProductImage[];
  translations: ProductTranslation[];
  createdAt: Date;
  updatedAt: Date;
}
```

**Order Interface:**
```typescript
interface Order {
  id: string;
  orderNumber: string;
  customerFirstName: string;
  customerLastName: string;
  customerEmail: string;
  customerPhone: string;
  shippingAddress: Address;
  subtotal: number;
  shippingCost: number;
  total: number;
  status: OrderStatus;
  confirmationStatus: ConfirmationStatus;
  paymentStatus: PaymentStatus;
  firstDeliveryId?: string;
  firstDeliveryTrackingNumber?: string;
  firstDeliveryStatus?: DeliveryStatus;
  items: OrderItem[];
  createdAt: Date;
  updatedAt: Date;
}
```

**Category Interface:**
```typescript
interface Category {
  id: string;
  name: string;
  slug: string;
  description: string;
  image?: string;
  parentId?: string;
  isActive: boolean;
  sortOrder: number;
  children?: Category[];
  translations: CategoryTranslation[];
  createdAt: Date;
  updatedAt: Date;
}
```

### State Management Types
**Redux State:**
```typescript
interface RootState {
  auth: AuthState;
  products: ProductsState;
  orders: OrdersState;
  customers: CustomersState;
  categories: CategoriesState;
  ui: UIState;
  language: LanguageState;
}
```

### Component Props Types
**Common Component Props:**
```typescript
interface BaseComponentProps {
  className?: string;
  children?: React.ReactNode;
  loading?: boolean;
  error?: string;
}

interface TableProps<T> extends BaseComponentProps {
  data: T[];
  columns: Column<T>[];
  onRowClick?: (row: T) => void;
  onSelectionChange?: (selected: T[]) => void;
}
```

## 🌍 Multilingual & RTL/LTR Support

### Language Support
- **French (fr)**: Primary language
- **Arabic (ar)**: Secondary language with RTL support
- **English (en)**: Fallback language

### Translation System
- **React i18next**: Internationalization framework
- **Translation Keys**: All text uses translation keys
- **Language Files**: JSON files organized by feature
- **Dynamic Translations**: Product names, descriptions, categories

### RTL/LTR Implementation
- **CSS Direction**: Dynamic `dir="rtl"` or `dir="ltr"`
- **Flexbox/Grid**: RTL-aware layouts
- **Icon Positioning**: Icons flip for RTL
- **Text Alignment**: Automatic alignment
- **Form Layouts**: RTL-adapted forms

### Language Switching
- **Language Selector**: Dropdown in top navigation
- **Local Storage**: Remember user preference
- **URL Structure**: `/admin?lang=ar` or `/admin?lang=fr`
- **Default Language**: French as default
- **Context Provider**: React context for language state

## 📱 Responsive Design

### Breakpoints
- **Mobile**: 320px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px+

### Mobile Features
- **Hamburger Menu**: Collapsible sidebar
- **Touch Gestures**: Swipe and tap interactions
- **Mobile Forms**: Touch-friendly inputs
- **Card Layouts**: Stacked content
- **FAB**: Floating action button for quick actions

### Tablet Features
- **Collapsible Sidebar**: Space-efficient navigation
- **Adjusted Layouts**: Optimized for tablet screens
- **Touch Targets**: Larger buttons and links
- **Split Views**: Side-by-side content

### Desktop Features
- **Full Sidebar**: Always visible navigation
- **Multi-column Layouts**: Efficient space usage
- **Hover Effects**: Interactive elements
- **Keyboard Navigation**: Full keyboard support

## 🔧 Frontend Technical Features

### Performance
- **Code Splitting**: Lazy load components and routes
- **Image Optimization**: WebP format, lazy loading
- **Bundle Optimization**: Tree shaking and minification
- **Caching Strategy**: Browser caching and service workers
- **Virtual Scrolling**: Handle large datasets efficiently
- **Memoization**: React.memo and useMemo optimization
- **Debouncing**: Optimize search and input handling

### Security
- **Input Sanitization**: XSS prevention in forms
- **File Upload Validation**: Client-side file type validation
- **Role-based UI**: Show/hide features based on user role
- **Secure Storage**: Encrypted local storage for sensitive data
- **HTTPS Enforcement**: Force secure connections
- **Content Security Policy**: CSP headers for security

### Data Management
- **State Management**: Redux Toolkit for global state
- **Local State**: React hooks for component state
- **Form State**: React Hook Form for form management
- **Data Validation**: Client-side validation with Yup/Zod
- **Error Boundaries**: Graceful error handling
- **Loading States**: Skeleton loaders and spinners

### UI/UX Features
- **Responsive Design**: Mobile-first approach
- **Accessibility**: WCAG 2.1 compliance
- **Keyboard Navigation**: Full keyboard support
- **Screen Reader**: ARIA labels and descriptions
- **High Contrast**: Dark mode support
- **Animations**: Smooth transitions with Framer Motion
- **Toast Notifications**: User feedback system

## 🎨 UX Design Patterns & Workflows

### User Experience Principles
- **Progressive Disclosure**: Show information when needed
- **Consistent Navigation**: Predictable menu structure
- **Visual Hierarchy**: Clear information organization
- **Feedback Loops**: Immediate response to user actions
- **Error Prevention**: Prevent mistakes before they happen
- **Accessibility First**: Inclusive design for all users
- **Task-Oriented Design**: Focus on user goals and workflows
- **Contextual Actions**: Show relevant actions based on current state

### Key User Workflows

#### 1. Order Processing Workflow
```
📱 NEW ORDER RECEIVED
    ↓
📞 CALL CUSTOMER (One-click)
    ↓
✅ CONFIRM ORDER (Quick action)
    ↓
🖨️ PRINT LABELS (Batch printing)
    ↓
📦 REQUEST PICKUP (Delivery integration)
    ↓
🚚 TRACK DELIVERY (Real-time updates)
    ↓
✅ MARK DELIVERED (Status update)
```

#### 2. Product Management Workflow
```
📦 ADD NEW PRODUCT
    ↓
📝 BASIC INFO (Name, SKU, Price)
    ↓
📁 CATEGORY SELECTION (Smart suggestions)
    ↓
🖼️ IMAGE UPLOAD (Drag & drop)
    ↓
📊 INVENTORY SETUP (Stock, alerts)
    ↓
✅ PUBLISH PRODUCT (Go live)
```

#### 3. Customer Service Workflow
```
👥 CUSTOMER INQUIRY
    ↓
🔍 SEARCH CUSTOMER (Quick find)
    ↓
📋 VIEW ORDER HISTORY (Complete context)
    ↓
💬 ADD NOTES (Internal tracking)
    ↓
📞 CONTACT CUSTOMER (One-click)
    ↓
✅ RESOLVE ISSUE (Status update)
```

### Interaction Patterns
- **Hover States**: Subtle animations on interactive elements
- **Loading States**: Clear feedback during data processing
- **Empty States**: Helpful messages when no data exists
- **Success States**: Confirmation of completed actions
- **Error States**: Clear error messages with solutions
- **Confirmation Dialogs**: Prevent accidental actions
- **Progressive Forms**: Multi-step forms with progress indicators
- **Smart Defaults**: Pre-filled forms based on context

### Mobile-First Design
- **Touch Targets**: Minimum 44px touch areas
- **Swipe Gestures**: Natural mobile interactions
- **Thumb Navigation**: Easy one-handed operation
- **Responsive Images**: Optimized for different screen sizes
- **Fast Loading**: Optimized for mobile networks
- **Offline Capability**: Basic functionality without internet
- **Pull to Refresh**: Natural mobile data refresh
- **Swipe Actions**: Quick actions on list items

### Performance UX
- **Skeleton Loading**: Show content structure while loading
- **Lazy Loading**: Load content as needed
- **Optimistic Updates**: Show changes immediately
- **Progressive Enhancement**: Core functionality first
- **Error Recovery**: Graceful handling of failures
- **Caching Strategy**: Smart data caching for speed

## 📊 Analytics & Reporting

### Dashboard Analytics
- **Sales Overview**: Daily, weekly, monthly sales
- **Product Performance**: Best and worst selling products
- **Customer Analytics**: Customer behavior and preferences
- **Order Trends**: Order patterns and seasonality
- **Revenue Tracking**: Financial performance metrics
- **Conversion Rates**: Cart to purchase conversion
- **Geographic Analytics**: Sales by location
- **Time-based Analytics**: Peak hours and days

### Reports
- **Sales Reports**: Revenue and sales analysis
- **Product Reports**: Product performance metrics
- **Customer Reports**: Customer acquisition and retention
- **Inventory Reports**: Stock levels and movement
- **Delivery Reports**: Shipping performance
- **Financial Reports**: Profit/loss statements
- **Custom Reports**: User-defined report builder
- **Comparative Reports**: Period-over-period analysis

### Export Options
- **CSV Export**: Data export for analysis
- **PDF Reports**: Formatted reports
- **Excel Export**: Spreadsheet compatibility
- **Scheduled Reports**: Automated report generation
- **Email Reports**: Automated email delivery
- **API Export**: Programmatic data access

## 🔔 Notifications & Alerts

### System Notifications
- **Low Stock Alerts**: Products needing restock
- **New Orders**: Real-time order notifications
- **Failed Deliveries**: Delivery issues requiring attention
- **System Alerts**: Maintenance and error notifications

### Email Notifications
- **Order Confirmations**: Customer order confirmations
- **Status Updates**: Order status changes
- **System Alerts**: Administrative notifications
- **Weekly Reports**: Automated performance reports

### In-App Notifications
- **Toast Messages**: Success/error feedback
- **Banner Alerts**: Important system messages
- **Badge Counters**: Unread notifications
- **Modal Alerts**: Critical system messages

## 🚀 Frontend Implementation Phases

### Phase 1: Core Foundation (Weeks 1-2)
- [ ] React app setup with TypeScript
- [ ] Tailwind CSS configuration with RTL support
- [ ] Basic layout components (Header, Sidebar, Main)
- [ ] Routing setup with React Router
- [ ] Redux store configuration
- [ ] i18next internationalization setup

### Phase 2: Authentication & Layout (Weeks 3-4)
- [ ] Login page with form validation
- [ ] Protected route wrapper
- [ ] User context and state management
- [ ] Responsive sidebar navigation
- [ ] Language switcher component
- [ ] Theme provider setup

### Phase 3: Dashboard & Analytics (Weeks 5-6)
- [ ] Dashboard overview page
- [ ] Metrics cards component
- [ ] Charts and data visualization
- [ ] Recent orders table
- [ ] Quick actions panel
- [ ] Real-time updates setup

### Phase 4: Product Management (Weeks 7-8)
- [ ] Product listing page with filters
- [ ] Product grid and table views
- [ ] Product create/edit forms
- [ ] Image upload with drag & drop
- [ ] Bulk operations interface
- [ ] Search and filtering components

### Phase 5: Order Management (Weeks 9-10)
- [ ] Order listing with status filters
- [ ] Order details modal/page
- [ ] Order status management
- [ ] Order workflow components
- [ ] Bulk order actions
- [ ] Order search functionality

### Phase 6: Customer Management (Weeks 11-12)
- [ ] Customer listing page
- [ ] Customer details view
- [ ] Customer create/edit forms
- [ ] Customer search and filters
- [ ] Customer communication interface
- [ ] Customer notes system

### Phase 7: Category Management (Weeks 13-14)
- [ ] Category tree component
- [ ] Category create/edit forms
- [ ] Drag & drop reordering
- [ ] Category image upload
- [ ] Bulk category operations
- [ ] Category search functionality

### Phase 8: Settings & Configuration (Weeks 15-16)
- [ ] Settings pages layout
- [ ] Form components for configuration
- [ ] User management interface
- [ ] Role-based UI components
- [ ] Settings validation
- [ ] Configuration panels

### Phase 9: Polish & Optimization (Weeks 17-18)
- [ ] UI/UX improvements and animations
- [ ] Performance optimization
- [ ] Mobile responsiveness enhancements
- [ ] Error handling and validation
- [ ] Testing setup and implementation
- [ ] Documentation and user guides

## 🎨 UI Components

### Design System
- **Color Palette**: Consistent color scheme
- **Typography**: Font hierarchy and sizing
- **Spacing**: Consistent margins and padding
- **Shadows**: Depth and elevation
- **Borders**: Border radius and styles
- **Icons**: Consistent icon library

### Component Library
- **Buttons**: Primary, secondary, danger variants
- **Forms**: Input fields, selects, checkboxes
- **Tables**: Sortable, filterable data tables
- **Modals**: Confirmation and form modals
- **Cards**: Content containers
- **Badges**: Status indicators
- **Charts**: Data visualization
- **Loading States**: Skeleton loaders and spinners

### Interactive Elements
- **Hover Effects**: Subtle animations
- **Transitions**: Smooth state changes
- **Loading States**: User feedback
- **Error States**: Clear error messaging
- **Success States**: Confirmation feedback
- **Empty States**: Helpful empty state messages

## 🔒 Security & Compliance

### Authentication
- **Laravel Sanctum**: API token authentication
- **Session Management**: Secure session handling
- **Password Policies**: Strong password requirements
- **Two-Factor Authentication**: Enhanced security (future)

### Authorization
- **Role-based Access**: Admin/Worker permissions
- **Permission Matrix**: Granular access control
- **Route Protection**: Middleware-based protection
- **Resource Authorization**: Model-level permissions

### Data Protection
- **Input Validation**: Server-side validation
- **XSS Prevention**: Output escaping
- **CSRF Protection**: Token-based protection
- **SQL Injection Prevention**: Parameterized queries
- **File Upload Security**: Secure file handling

### Privacy Compliance
- **GDPR Compliance**: Data protection regulations
- **Data Retention**: Automated data cleanup
- **User Consent**: Privacy policy acceptance
- **Data Export**: User data export functionality

## 📈 Performance Metrics

### Key Performance Indicators
- **Page Load Time**: < 2 seconds
- **Time to Interactive**: < 3 seconds
- **First Contentful Paint**: < 1.5 seconds
- **Largest Contentful Paint**: < 2.5 seconds
- **Cumulative Layout Shift**: < 0.1

### Optimization Strategies
- **Image Optimization**: WebP format, lazy loading
- **Code Splitting**: JavaScript bundle optimization
- **Caching**: Redis/Memcached implementation
- **CDN**: Content delivery network
- **Database Optimization**: Query optimization, indexing

### Monitoring
- **Performance Monitoring**: Real-time metrics
- **Error Tracking**: Error logging and reporting
- **User Analytics**: Usage patterns and behavior
- **System Health**: Server and database monitoring

## 🧪 Testing Strategy

### Testing Types
- **Unit Tests**: Individual component testing
- **Integration Tests**: API and database testing
- **Feature Tests**: End-to-end functionality
- **Browser Tests**: Cross-browser compatibility
- **Performance Tests**: Load and stress testing

### Test Coverage
- **Code Coverage**: > 80% coverage target
- **Critical Paths**: 100% coverage for critical features
- **Edge Cases**: Boundary condition testing
- **Error Scenarios**: Error handling validation

### Quality Assurance
- **Code Review**: Peer review process
- **Automated Testing**: CI/CD pipeline
- **Manual Testing**: User acceptance testing
- **Accessibility Testing**: WCAG compliance
- **Security Testing**: Vulnerability assessment

## 📚 Documentation

### User Documentation
- **Admin Guide**: Complete user manual
- **Video Tutorials**: Step-by-step guides
- **FAQ Section**: Common questions and answers
- **Best Practices**: Recommended workflows

### Technical Documentation
- **API Documentation**: Endpoint specifications
- **Database Schema**: Entity relationship diagrams
- **Code Documentation**: Inline code comments
- **Deployment Guide**: Setup and configuration

### Maintenance Documentation
- **Troubleshooting Guide**: Common issues and solutions
- **Backup Procedures**: Data backup and recovery
- **Update Procedures**: System update processes
- **Monitoring Guide**: System monitoring and alerts

## 🚀 Frontend Deployment

### Build Process
**Development Build:**
- **Development Server**: Vite dev server
- **Hot Reload**: Instant updates during development
- **Source Maps**: Debug-friendly code mapping
- **TypeScript**: Type checking and compilation
- **ESLint/Prettier**: Code quality and formatting

**Production Build:**
- **Bundle Optimization**: Webpack/Vite optimization
- **Code Splitting**: Lazy loading and chunking
- **Asset Optimization**: Image and file compression
- **Tree Shaking**: Remove unused code
- **Minification**: CSS and JavaScript minification

### Deployment Options
**Static Hosting:**
- **Vercel**: Optimized for React applications
- **Netlify**: CDN and form handling
- **AWS S3 + CloudFront**: Scalable static hosting
- **GitHub Pages**: Free hosting for public repos

**CDN Integration:**
- **CloudFlare**: Global CDN and security
- **AWS CloudFront**: AWS CDN service
- **KeyCDN**: High-performance CDN
- **BunnyCDN**: Cost-effective CDN solution

### Performance Optimization
**Build Optimizations:**
- **Bundle Analysis**: Webpack bundle analyzer
- **Code Splitting**: Route-based and component-based
- **Lazy Loading**: Dynamic imports for components
- **Image Optimization**: WebP format and lazy loading
- **Caching Strategy**: Browser and CDN caching

**Runtime Optimizations:**
- **Service Workers**: Offline functionality
- **Preloading**: Critical resource preloading
- **Prefetching**: Predictive resource loading
- **Compression**: Gzip/Brotli compression
- **HTTP/2**: Modern protocol support

## 🚀 Future Enhancements

### Planned Features
- **Advanced Analytics**: Detailed reporting dashboard
- **Inventory Management**: Advanced stock tracking
- **Customer Segmentation**: Advanced customer grouping
- **Marketing Tools**: Email campaigns and promotions
- **Mobile App**: Native mobile application
- **API Integration**: Third-party service integrations
- **AI/ML Features**: Predictive analytics, recommendation engine
- **Advanced Search**: Elasticsearch integration
- **Real-time Chat**: Customer support chat
- **Video Support**: Product video management

### Scalability Considerations
- **Microservices Architecture**: Service decomposition
- **Load Balancing**: Horizontal scaling
- **Database Sharding**: Data partitioning
- **Caching Strategy**: Multi-level caching
- **CDN Integration**: Global content delivery
- **Container Orchestration**: Kubernetes deployment
- **Auto-scaling**: Dynamic resource allocation
- **Multi-region**: Global deployment strategy

### Technology Upgrades
- **React Updates**: Framework version upgrades
- **TypeScript**: Enhanced type safety
- **UI Library**: Component library updates
- **Build Tools**: Vite/Webpack optimization
- **State Management**: Redux Toolkit updates
- **Testing**: Jest/React Testing Library updates
- **Progressive Web App**: PWA capabilities
- **Web Components**: Modern component architecture

---

## 📞 Support & Contact

### Technical Support
- **Email**: support@yakinemode.com
- **Phone**: +212 XXX XXX XXX
- **Documentation**: https://docs.yakinemode.com
- **Issue Tracking**: GitHub Issues

### Development Team
- **Lead Developer**: [Name]
- **UI/UX Designer**: [Name]
- **Backend Developer**: [Name]
- **Frontend Developer**: [Name]
- **QA Engineer**: [Name]

---

*This PRD serves as the comprehensive specification for the Yakine Mode Admin Dashboard. It outlines all features, functionality, and technical requirements necessary for successful implementation and deployment.*
