// Live Search functionality for Dashboard
console.log('Live search script loaded');

class LiveSearch {
    constructor() {
        this.searchInput = null;
        this.resultsContainer = null;
        this.searchTimeout = null;
        this.isSearching = false;
        this.currentFilters = {};
        this.debounceDelay = 800; // 800ms delay - reduced API calls
        
        this.init();
    }
    
    init() {
        // Only run on dashboard page
        if (!this.isDashboardPage()) {
            return;
        }
        
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        } else {
            // Try immediately, but also set up a retry in case elements aren't ready
            this.setupEventListeners();
            
            // If setup failed, retry after a short delay
            if (!this.searchInput) {
                setTimeout(() => this.setupEventListeners(), 100);
            }
        }
    }
    
    isDashboardPage() {
        // Check if we're on the dashboard page
        const currentPath = window.location.pathname;
        const isDashboard = currentPath.includes('/admin/dashboard') || currentPath === '/admin' || currentPath === '/admin/';
        
        // Also check for dashboard-specific elements
        const hasDashboardElements = document.querySelector('#orders-results-container') !== null;
        
        console.log('Current path:', currentPath, 'Is dashboard:', isDashboard, 'Has dashboard elements:', hasDashboardElements);
        
        return isDashboard || hasDashboardElements;
    }
    
    setupEventListeners() {
        // Find search input - be more specific
        this.searchInput = document.querySelector('input[name="search"]');
        this.resultsContainer = document.querySelector('#orders-results-container');
        
        if (!this.searchInput) {
            // Try to find it with a more specific selector
            this.searchInput = document.querySelector('input[type="text"][placeholder*="Order"]');
            if (!this.searchInput) {
                return;
            }
        }
        
        if (!this.resultsContainer) {
            return;
        }
        
        // Add event listeners
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e);
        });
        this.searchInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        
        // Setup clear search button
        this.setupClearSearchButton();
        
        // Listen for filter changes
        this.setupFilterListeners();
        
        // Add loading indicator
        this.addLoadingIndicator();
    }
    
    setupClearSearchButton() {
        const clearButton = document.querySelector('#clear-search');
        if (!clearButton) return;
        
        clearButton.addEventListener('click', () => {
            console.log('Clear search clicked');
            this.searchInput.value = '';
            this.showAllResults();
            this.toggleClearButton(false);
        });
        
        // Show/hide clear button based on input value
        this.searchInput.addEventListener('input', () => {
            this.toggleClearButton(this.searchInput.value.length > 0);
        });
        
        // Initial state
        this.toggleClearButton(this.searchInput.value.length > 0);
    }
    
    toggleClearButton(show) {
        const clearButton = document.querySelector('#clear-search');
        if (!clearButton) return;
        
        if (show) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
    }
    
    setupFilterListeners() {
        // Status filter
        const statusFilter = document.querySelector('select[name="status"]');
        if (statusFilter) {
            statusFilter.addEventListener('change', () => {
                console.log('Status filter changed');
                this.updateFilters();
            });
        }
        
        // Confirmation status filter
        const confirmationStatusFilter = document.querySelector('select[name="confirmation_status"]');
        if (confirmationStatusFilter) {
            confirmationStatusFilter.addEventListener('change', () => {
                console.log('Confirmation status filter changed');
                this.updateFilters();
            });
        }
        
        // Date filters
        const dateFromFilter = document.querySelector('input[name="date_from"]');
        if (dateFromFilter) {
            dateFromFilter.addEventListener('change', () => {
                console.log('Date from filter changed');
                this.updateFilters();
            });
        }
        
        const dateToFilter = document.querySelector('input[name="date_to"]');
        if (dateToFilter) {
            dateToFilter.addEventListener('change', () => {
                console.log('Date to filter changed');
                this.updateFilters();
            });
        }
    }
    
    handleSearchInput(event) {
        const searchTerm = event.target.value.trim();
        
        // Prevent form submission
        event.preventDefault();
        
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        // If search term is empty, show all results
        if (searchTerm === '') {
            this.showAllResults();
            return;
        }
        
        // Only search if we have at least 2 characters
        if (searchTerm.length < 2) {
            return;
        }
        
        // Set loading state
        this.setLoadingState(true);
        
        // Debounce the search
        this.searchTimeout = setTimeout(() => {
            this.performSearch(searchTerm);
        }, this.debounceDelay);
    }
    
    handleKeyDown(event) {
        // Prevent form submission on Enter key
        if (event.key === 'Enter') {
            event.preventDefault();
            this.performSearch(this.searchInput.value.trim());
        }
        
        // Clear search on Escape
        if (event.key === 'Escape') {
            this.searchInput.value = '';
            this.showAllResults();
        }
    }
    
    updateFilters() {
        // Update current filters
        this.currentFilters = {
            status: document.querySelector('select[name="status"]')?.value || '',
            confirmation_status: document.querySelector('select[name="confirmation_status"]')?.value || '',
            date_from: document.querySelector('input[name="date_from"]')?.value || '',
            date_to: document.querySelector('input[name="date_to"]')?.value || ''
        };
        
        // Perform search with current search term and filters
        const searchTerm = this.searchInput.value.trim();
        if (searchTerm) {
            this.performSearch(searchTerm);
        } else {
            this.showAllResults();
        }
    }
    
    async performSearch(searchTerm) {
        if (this.isSearching) {
            return;
        }
        
        this.isSearching = true;
        this.setLoadingState(true);
        
        try {
            // Build query parameters
            const params = new URLSearchParams({
                search: searchTerm,
                ...this.currentFilters
            });
            
            // Make API request
            const response = await fetch(`/admin/orders/search?${params}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.displaySearchResults(data.data);
            } else {
                throw new Error(data.message || 'Search failed');
            }
            
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please try again.');
        } finally {
            this.isSearching = false;
            this.setLoadingState(false);
        }
    }
    
    displaySearchResults(orders) {
        if (!this.resultsContainer) {
            return;
        }
        
        // Update the results container with new data
        this.updateOrdersTable(orders);
        
        // Show results count
        this.showResultsCount(orders.length);
    }
    
    updateOrdersTable(orders) {
        // Find the table body
        const tableBody = document.querySelector('tbody');
        const mobileContainer = document.querySelector('.lg\\:hidden.space-y-4.p-4');
        
        if (!tableBody && !mobileContainer) {
            return;
        }
        
        // Update desktop table
        if (tableBody) {
            tableBody.innerHTML = this.generateDesktopTableRows(orders);
        }
        
        // Update mobile cards
        if (mobileContainer) {
            mobileContainer.innerHTML = this.generateMobileCards(orders);
        }
        
        // Re-attach event listeners for the new content
        this.attachOrderEventListeners();
    }
    
    generateDesktopTableRows(orders) {
        if (orders.length === 0) {
            return `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria</p>
                    </td>
                </tr>
            `;
        }
        
        return orders.map(order => {
            const statusColors = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-green-100 text-green-800',
                'declined': 'bg-red-100 text-red-800',
                'processing': 'bg-blue-100 text-blue-800',
                'shipped': 'bg-purple-100 text-purple-800',
                'delivered': 'bg-green-100 text-green-800',
                'cancelled': 'bg-gray-100 text-gray-800',
            };
            
            const statusClass = statusColors[order.status] || 'bg-gray-100 text-gray-800';
            const shippingAddress = typeof order.shipping_address === 'string' 
                ? order.shipping_address 
                : JSON.stringify(order.shipping_address);
            
            return `
                <tr class="hover:bg-gray-50 cursor-pointer" 
                    onclick="openQuickView(${JSON.stringify(order).replace(/"/g, '&quot;')})">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="text-indigo-600 hover:text-indigo-900">
                            ${order.order_number}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${order.customer_first_name || ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${order.customer_last_name || ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${this.truncateText(shippingAddress, 30)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${order.customer_phone || 'N/A'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                            ${this.getStatusText(order.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${order.created_at}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${parseFloat(order.total).toFixed(2)} د.م
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openQuickView(${JSON.stringify(order).replace(/"/g, '&quot;')})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            View
                        </button>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            Edit
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    generateMobileCards(orders) {
        if (orders.length === 0) {
            return `
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria</p>
                </div>
            `;
        }
        
        return orders.map(order => {
            const statusColors = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-green-100 text-green-800',
                'declined': 'bg-red-100 text-red-800',
                'processing': 'bg-blue-100 text-blue-800',
                'shipped': 'bg-purple-100 text-purple-800',
                'delivered': 'bg-green-100 text-green-800',
                'cancelled': 'bg-gray-100 text-gray-800',
            };
            
            const statusClass = statusColors[order.status] || 'bg-gray-100 text-gray-800';
            
            return `
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">#${order.order_number}</h4>
                            <p class="text-sm text-gray-500">${order.created_at}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                            ${this.getStatusText(order.status)}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Customer</span>
                            <span class="text-sm font-medium text-gray-900">${order.customer_first_name} ${order.customer_last_name}</span>
                        </div>
                        ${order.customer_phone ? `
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Phone</span>
                            <a href="tel:${order.customer_phone}" class="text-sm text-blue-600">${order.customer_phone}</a>
                        </div>
                        ` : ''}
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total</span>
                            <span class="text-sm font-bold text-gray-900">${parseFloat(order.total).toFixed(2)} د.م</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button onclick="openQuickView(${JSON.stringify(order).replace(/"/g, '&quot;')})" 
                                class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            View
                        </button>
                        <a href="#" class="flex-1 bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                            Edit
                        </a>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    attachOrderEventListeners() {
        // Re-attach any necessary event listeners for the new content
        // This is handled by the global openQuickView function
    }
    
    showAllResults() {
        console.log('Showing all results (updating dynamically)');
        // Update results dynamically instead of reloading
        // window.location.reload();
    }
    
    showResultsCount(count) {
        // Update results count if there's a counter element
        const countElement = document.querySelector('#results-count');
        if (countElement) {
            if (count > 0) {
                countElement.textContent = `${count} results found`;
                countElement.classList.remove('hidden');
            } else {
                countElement.classList.add('hidden');
            }
        }
    }
    
    setLoadingState(loading) {
        const searchInput = this.searchInput;
        const loadingSpinner = document.querySelector('#search-loading');
        
        if (!searchInput) return;
        
        if (loading) {
            searchInput.classList.add('opacity-50');
            searchInput.disabled = true;
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }
        } else {
            searchInput.classList.remove('opacity-50');
            searchInput.disabled = false;
            if (loadingSpinner) {
                loadingSpinner.classList.add('hidden');
            }
        }
    }
    
    addLoadingIndicator() {
        // Add a loading spinner next to the search input
        const searchContainer = this.searchInput?.parentElement;
        if (!searchContainer) return;
        
        const loadingSpinner = document.createElement('div');
        loadingSpinner.id = 'search-loading';
        loadingSpinner.className = 'hidden absolute right-3 top-1/2 transform -translate-y-1/2';
        loadingSpinner.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        
        searchContainer.style.position = 'relative';
        searchContainer.appendChild(loadingSpinner);
    }
    
    showError(message) {
        console.error('Showing error:', message);
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.textContent = message;
        
        const container = this.resultsContainer || document.querySelector('.space-y-6');
        if (container) {
            container.insertBefore(errorDiv, container.firstChild);
            
            // Remove error after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    }
    
    truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
    
    getStatusText(status) {
        const statusTexts = {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'declined': 'Declined',
            'processing': 'Processing',
            'shipped': 'Shipped',
            'delivered': 'Delivered',
            'cancelled': 'Cancelled'
        };
        return statusTexts[status] || status;
    }
}

// Initialize live search when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Prevent multiple initializations
    if (window.liveSearchInstance) {
        return;
    }
    window.liveSearchInstance = new LiveSearch();
});

// Export for potential use in other modules
window.LiveSearch = LiveSearch;