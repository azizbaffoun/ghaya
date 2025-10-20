/**
 * Smooth Navigation Service
 * Handles client-side navigation without page reloads
 */
class SmoothNavigation {
    constructor() {
        this.currentUrl = window.location.href;
        this.isNavigating = false;
        this.init();
    }

    init() {
        // Intercept all navigation clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[data-smooth-nav]');
            if (link && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                e.preventDefault();
                this.navigate(link.href);
            }
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.smoothNav) {
                this.loadPage(window.location.href, false);
            }
        });
    }

    async navigate(url) {
        if (this.isNavigating || url === this.currentUrl) return;
        
        this.isNavigating = true;
        this.showLoadingState();

        try {
            await this.loadPage(url);
            this.currentUrl = url;
            
            // Update browser history
            history.pushState({ smoothNav: true }, '', url);
            
        } catch (error) {
            console.error('Navigation error:', error);
            // Fallback to normal navigation
            window.location.href = url;
        } finally {
            this.hideLoadingState();
            this.isNavigating = false;
        }
    }

    async loadPage(url, showTransition = true) {
        try {
            // Add transition class if needed
            if (showTransition) {
                document.body.classList.add('page-transitioning');
            }

            // Fetch the new page content
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extract the main content
            const newContent = doc.querySelector('#main-content');
            const currentContent = document.querySelector('#main-content');

            if (newContent && currentContent) {
                // Update page title
                document.title = doc.title;

                // Extract and update page metadata
                this.updatePageMetadata(doc);

                // Update the main content with smooth transition
                if (showTransition) {
                    currentContent.style.opacity = '0';
                    currentContent.style.transform = 'translateY(20px)';
                    
                    await this.wait(150);
                }

                currentContent.innerHTML = newContent.innerHTML;

                if (showTransition) {
                    currentContent.style.opacity = '1';
                    currentContent.style.transform = 'translateY(0)';
                    
                    await this.wait(150);
                }

                // Update sidebar and header based on new URL
                this.updateSidebarActiveState(url);
                this.updateHeader(doc);

                // Reinitialize any components that need it
                this.reinitializeComponents();
            }

        } catch (error) {
            console.error('Error loading page:', error);
            throw error;
        } finally {
            if (showTransition) {
                document.body.classList.remove('page-transitioning');
            }
        }
    }

    reinitializeComponents() {
        // Reinitialize Alpine.js components
        if (window.Alpine) {
            Alpine.initTree(document.querySelector('#main-content'));
        }

        // Reinitialize any other components
        this.initializeBulkSelection();
        this.initializeOrderManagement();
    }

    initializeBulkSelection() {
        // Reinitialize bulk selection if on order pages
        if (window.initializeBulkSelection) {
            window.initializeBulkSelection();
        }
    }

    initializeOrderManagement() {
        // Reinitialize order management if on order pages
        if (window.initializeOrderManagement) {
            window.initializeOrderManagement();
        }
    }

    showLoadingState() {
        // Create or show loading indicator
        let loader = document.querySelector('#smooth-nav-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'smooth-nav-loader';
            loader.className = 'fixed top-4 right-4 z-50 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg';
            loader.innerHTML = `
                <div class="flex items-center space-x-2">
                    <div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                    <span>Loading...</span>
                </div>
            `;
            document.body.appendChild(loader);
        }
        loader.style.display = 'block';
    }

    hideLoadingState() {
        const loader = document.querySelector('#smooth-nav-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    wait(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    updatePageMetadata(doc) {
        // Extract page metadata from the loaded document
        this.pageTitle = doc.querySelector('title')?.textContent || '';
        this.pageSubtitle = doc.querySelector('meta[name="description"]')?.getAttribute('content') || '';
        
        // Try to extract route information from the page
        const routeElement = doc.querySelector('[data-current-route]');
        this.currentRoute = routeElement?.getAttribute('data-current-route') || this.extractRouteFromUrl(window.location.href);
    }

    extractRouteFromUrl(url) {
        // Extract route name from URL for matching with sidebar links
        const path = new URL(url).pathname;
        
        // Map common admin paths to route names
        const routeMap = {
            '/admin': 'admin.dashboard',
            '/admin/dashboard': 'admin.dashboard',
            '/admin/products': 'admin.products.index',
            '/admin/categories': 'admin.categories.index',
            '/admin/orders': 'admin.orders.index',
            '/admin/orders/new': 'admin.orders.new',
            '/admin/orders/confirmed': 'admin.orders.confirmed',
            '/admin/orders/ready-pickup': 'admin.orders.ready-pickup',
            '/admin/orders/in-delivery': 'admin.orders.in-delivery',
            '/admin/customers': 'admin.customers.index',
            '/admin/page-builder': 'admin.page-builder.index',
            '/admin/banners': 'admin.banners.index',
            '/admin/delivery/settings': 'admin.delivery.settings',
            '/admin/languages': 'admin.languages.index'
        };

        return routeMap[path] || path;
    }

    updateHeader(doc) {
        // Update the header title and subtitle
        const headerTitle = document.querySelector('header h2');
        const headerSubtitle = document.querySelector('header p');
        
        if (headerTitle) {
            // Extract title from the loaded page's @section('title')
            const newTitle = this.extractPageTitle(doc);
            if (newTitle) {
                headerTitle.textContent = newTitle;
            }
        }
        
        if (headerSubtitle) {
            // Extract subtitle from the loaded page
            const newSubtitle = this.extractPageSubtitle(doc);
            if (newSubtitle) {
                headerSubtitle.textContent = newSubtitle;
            }
        }
    }

    extractPageTitle(doc) {
        // Try to find the page title from various sources
        const titleElement = doc.querySelector('[data-page-title]');
        if (titleElement) {
            return titleElement.getAttribute('data-page-title');
        }

        // Look for h1 elements that might contain the page title
        const h1Elements = doc.querySelectorAll('h1');
        for (const h1 of h1Elements) {
            const text = h1.textContent.trim();
            if (text && text !== 'Dashboard' && text !== 'Tableau de bord') {
                return text;
            }
        }

        // Fallback to document title
        return doc.title.replace(/^[^-]+ - /, '');
    }

    extractPageSubtitle(doc) {
        // Look for subtitle elements
        const subtitleElement = doc.querySelector('[data-page-subtitle]');
        if (subtitleElement) {
            return subtitleElement.getAttribute('data-page-subtitle');
        }

        // Look for p elements after h1 that might be subtitles
        const h1Elements = doc.querySelectorAll('h1');
        for (const h1 of h1Elements) {
            const nextP = h1.nextElementSibling;
            if (nextP && nextP.tagName === 'P') {
                const text = nextP.textContent.trim();
                if (text) {
                    return text;
                }
            }
        }

        return null;
    }

    updateSidebarActiveState(url) {
        // Clear all active states
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('active', 'bg-blue-600', 'bg-opacity-20', 'border-blue-400');
            item.classList.add('bg-white', 'bg-opacity-5');
        });

        // Find the current route
        const currentRoute = this.extractRouteFromUrl(url);
        
        // Find matching sidebar link
        const matchingLink = document.querySelector(`[data-route="${currentRoute}"]`);
        if (matchingLink) {
            this.setActiveSidebarItem(matchingLink);
        } else {
            // Try to match by page type for submenu items
            const pageType = this.getPageTypeFromUrl(url);
            if (pageType) {
                const parentButton = document.querySelector(`[data-page-type="${pageType}"]`);
                if (parentButton) {
                    this.setActiveSidebarItem(parentButton);
                    this.expandParentMenu(parentButton);
                }
            }
        }
    }

    getPageTypeFromUrl(url) {
        const path = new URL(url).pathname;
        
        if (path.includes('/orders/')) return 'orders';
        if (path.includes('/delivery/')) return 'delivery';
        
        return null;
    }

    setActiveSidebarItem(element) {
        // Add active classes
        element.classList.add('active', 'bg-blue-600', 'bg-opacity-20', 'border-blue-400');
        element.classList.remove('bg-white', 'bg-opacity-5');
    }

    expandParentMenu(activeElement) {
        // If this is a submenu item, expand its parent
        const parentMenu = activeElement.closest('[x-data*="ordersOpen"], [x-data*="deliveryOpen"]');
        if (parentMenu) {
            // Find the Alpine.js data and set the open state
            const alpineData = parentMenu.getAttribute('x-data');
            if (alpineData.includes('ordersOpen')) {
                // Trigger orders menu expansion
                const ordersButton = parentMenu.querySelector('[data-page-type="orders"]');
                if (ordersButton) {
                    ordersButton.click();
                }
            } else if (alpineData.includes('deliveryOpen')) {
                // Trigger delivery menu expansion
                const deliveryButton = parentMenu.querySelector('[data-page-type="delivery"]');
                if (deliveryButton) {
                    deliveryButton.click();
                }
            }
        }
    }
}

// Initialize smooth navigation
document.addEventListener('DOMContentLoaded', () => {
    window.smoothNavigation = new SmoothNavigation();
});

// Export for use in other modules
window.SmoothNavigation = SmoothNavigation;
