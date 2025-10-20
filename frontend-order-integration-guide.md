# Frontend Order Integration Guide - First Delivery API

## Overview

This guide explains how the React frontend should integrate with the Laravel backend for the complete order flow, including First Delivery API integration for shipping and delivery management.

## Current E-commerce Flow

### 1. Product Selection & Cart Management
- ✅ **Already implemented**: Fetch items, categories, create cart
- Users can browse products, select sizes, colors, quantities
- Cart management (add/remove items, update quantities)

### 2. Order Creation Flow (What needs to be implemented)

#### Step 1: Cart to Order Conversion
When user clicks "Checkout" or "Place Order":

```javascript
// Frontend should send this data to Laravel API
const orderData = {
  // Customer Information
  customer_first_name: "John",
  customer_last_name: "Doe", 
  customer_email: "john.doe@example.com",
  customer_phone: "12345678", // Will be formatted to +216 XX XXX XXX
  
  // Shipping Address
  shipping_address: "123 Main Street, Apt 4B",
  shipping_city: "Tunis",
  shipping_state: "Tunis", 
  shipping_postal_code: "1000",
  shipping_country: "Tunisia",
  
  // Order Details
  subtotal: 150.00,        // Sum of all items before shipping
  shipping_cost: 8.00,     // Calculated shipping cost
  total: 158.00,           // subtotal + shipping_cost
  
  // Payment & Notes
  payment_method: "cash_on_delivery", // or "credit_card", "bank_transfer"
  notes: "Please deliver after 5 PM", // Optional customer notes
  
  // Cart Items (converted from cart)
  items: [
    {
      product_id: 1,
      product_name: "T-Shirt Red",
      product_sku: "TSH-RED-M",
      quantity: 2,
      unit_price: 25.00,
      total_price: 50.00,
      // Size/Color selections
      size: "M",
      color: "Red"
    },
    {
      product_id: 2, 
      product_name: "Jeans Blue",
      product_sku: "JNS-BLU-L",
      quantity: 1,
      unit_price: 100.00,
      total_price: 100.00,
      size: "L",
      color: "Blue"
    }
  ]
}
```

#### Step 2: API Endpoint
**POST** `/api/orders`

**Request Headers:**
```javascript
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {user_token}" // If user is logged in
}
```

**Response:**
```javascript
{
  "success": true,
  "message": "Order created successfully",
  "order": {
    "id": 123,
    "order_number": "ORD-2025-0001",
    "status": "pending",
    "confirmation_status": "pending_confirmation",
    "payment_status": "pending",
    "total": 158.00,
    "created_at": "2025-01-20T10:30:00Z",
    // ... other order fields
  }
}
```

## Backend Processing (What happens after order creation)

### 1. Order Status Flow
```
User Creates Order → pending_confirmation → Admin Confirms → confirmed → First Delivery Sync
```

### 2. Admin Confirmation Process
When admin confirms the order in the admin panel:

1. **Order Status Update**: `pending_confirmation` → `confirmed`
2. **First Delivery API Call**: Order data sent to First Delivery
3. **Response Processing**: 
   - `barcode` generated for tracking
   - `print_url` generated for shipping labels
   - Order marked as ready for pickup

### 3. First Delivery Integration
The backend automatically:

```php
// What happens in Laravel when admin confirms order
$firstDeliveryResponse = {
  "status": 201,
  "isError": false,
  "message": "Produit ajouté avec succès",
  "result": {
    "barCode": "683375045049",           // Tracking barcode
    "link": "https://firstdelivery.com/print?q=eyji"  // Print URL for shipping label
  }
}

// Order gets updated with:
order.barcode = "683375045049"
order.print_url = "https://firstdelivery.com/print?q=eyji"
order.first_delivery_response = { /* full response */ }
```

## Frontend Implementation Requirements

### 1. Order Confirmation Page
After successful order creation, show:

```jsx
const OrderConfirmation = ({ order }) => {
  return (
    <div className="order-confirmation">
      <h1>Order Confirmed!</h1>
      <p>Order Number: {order.order_number}</p>
      <p>Status: {order.confirmation_status}</p>
      
      {/* Show different messages based on status */}
      {order.confirmation_status === 'pending_confirmation' && (
        <div className="alert info">
          <p>Your order is being processed. You'll receive a confirmation email once it's confirmed.</p>
        </div>
      )}
      
      {order.confirmation_status === 'confirmed' && order.barcode && (
        <div className="alert success">
          <p>Order confirmed! Tracking barcode: {order.barcode}</p>
          <p>You can track your order using this barcode.</p>
        </div>
      )}
      
      <div className="order-details">
        <h3>Order Summary</h3>
        <p>Total: {order.total} TND</p>
        <p>Payment Method: {order.payment_method}</p>
        <p>Shipping Address: {order.shipping_address}</p>
      </div>
    </div>
  )
}
```

### 2. Order Tracking (Optional)
If you want to implement order tracking:

```javascript
// API endpoint to check order status
const checkOrderStatus = async (orderNumber) => {
  const response = await fetch(`/api/orders/${orderNumber}/status`)
  return response.json()
}

// Usage
const orderStatus = await checkOrderStatus('ORD-2025-0001')
console.log(orderStatus.confirmation_status) // 'confirmed', 'pending_confirmation', etc.
```

### 3. Error Handling
Handle different scenarios:

```javascript
const createOrder = async (orderData) => {
  try {
    const response = await fetch('/api/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(orderData)
    })
    
    const result = await response.json()
    
    if (result.success) {
      // Redirect to confirmation page
      router.push(`/order-confirmation/${result.order.id}`)
    } else {
      // Show error message
      setError(result.message)
    }
  } catch (error) {
    setError('Failed to create order. Please try again.')
  }
}
```

## API Endpoints Summary

### Frontend → Backend
| Method | Endpoint | Purpose | Status |
|--------|----------|---------|--------|
| GET | `/api/products` | Fetch products | ✅ Implemented |
| GET | `/api/categories` | Fetch categories | ✅ Implemented |
| POST | `/api/orders` | Create order | 🔄 **Needs Implementation** |
| GET | `/api/orders/{id}` | Get order details | 🔄 **Needs Implementation** |
| GET | `/api/orders/{id}/status` | Check order status | 🔄 **Optional** |

### Backend → First Delivery
| Method | Endpoint | Purpose | Status |
|--------|----------|---------|--------|
| POST | `/create` | Create delivery order | ✅ Implemented |
| POST | `/etat` | Check delivery status | ✅ Implemented |
| POST | `/pickup` | Request pickup | ✅ Implemented |
| POST | `/cancel-orders` | Cancel delivery | ✅ Implemented |

## Database Schema (for reference)

### Orders Table
```sql
orders:
- id (primary key)
- order_number (unique)
- customer_first_name
- customer_last_name  
- customer_email
- customer_phone
- shipping_address
- shipping_city
- shipping_state
- shipping_postal_code
- shipping_country
- subtotal
- shipping_cost
- total
- status (pending, confirmed, shipped, delivered, cancelled)
- confirmation_status (pending_confirmation, confirmed, printed, pickup_requested, in_delivery, delivered, cancelled)
- payment_status (pending, paid, failed, refunded)
- payment_method (cash_on_delivery, credit_card, bank_transfer)
- notes
- barcode (from First Delivery)
- print_url (from First Delivery)
- first_delivery_response (JSON)
- created_at
- updated_at
```

## Implementation Checklist

### Phase 1: Basic Order Creation
- [ ] Create order form component
- [ ] Implement cart to order conversion
- [ ] Add order confirmation page
- [ ] Handle form validation
- [ ] Add error handling

### Phase 2: Order Management
- [ ] Create order details page
- [ ] Add order status tracking
- [ ] Implement order history (if user logged in)
- [ ] Add order search functionality

### Phase 3: Advanced Features
- [ ] Real-time order status updates
- [ ] Email notifications
- [ ] Order modification (before confirmation)
- [ ] Bulk order operations

## Testing

### Test Order Creation
```javascript
// Test data for development
const testOrder = {
  customer_first_name: "Test",
  customer_last_name: "User",
  customer_email: "test@example.com",
  customer_phone: "12345678",
  shipping_address: "123 Test Street",
  shipping_city: "Tunis",
  shipping_state: "Tunis",
  shipping_postal_code: "1000",
  shipping_country: "Tunisia",
  subtotal: 100.00,
  shipping_cost: 8.00,
  total: 108.00,
  payment_method: "cash_on_delivery",
  notes: "Test order",
  items: [
    {
      product_id: 1,
      product_name: "Test Product",
      product_sku: "TEST-001",
      quantity: 1,
      unit_price: 100.00,
      total_price: 100.00
    }
  ]
}
```

## Notes for Development Team

1. **Phone Number Formatting**: The backend automatically formats Tunisian phone numbers to `+216 XX XXX XXX` format
2. **Currency**: All amounts are in TND (Tunisian Dinar)
3. **Order Numbers**: Auto-generated in format `ORD-YYYY-XXXX`
4. **Status Flow**: Orders start as `pending_confirmation` and move through various states
5. **First Delivery Integration**: Happens automatically when admin confirms orders
6. **Error Handling**: Always check `success` field in API responses
7. **Loading States**: Show loading indicators during API calls
8. **Form Validation**: Validate all required fields before submission

## Support

For any questions about the API integration or order flow, refer to:
- Laravel backend documentation
- First Delivery API documentation (first.md)
- This integration guide

---

**Last Updated**: January 20, 2025  
**Version**: 1.0  
**Status**: Ready for Implementation

