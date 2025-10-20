/**
 * Mobile Dashboard Enhancements
 * Touch optimizations, swipe gestures, and mobile-specific interactions
 */

// Prevent 300ms tap delay on mobile
document.addEventListener('DOMContentLoaded', function() {
    // Add fastclick behavior to all touch targets
    const touchTargets = document.querySelectorAll('.touch-target, button, a, input, select, textarea');
    touchTargets.forEach(target => {
        target.addEventListener('touchstart', function(e) {
            // Add active state for visual feedback
            this.classList.add('active');
        }, { passive: true });
        
        target.addEventListener('touchend', function(e) {
            // Remove active state
            this.classList.remove('active');
        }, { passive: true });
    });

    // Initialize mobile features
    initSwipeGestures();
    initMobileModals();
    initOrientationHandling();
    initFABBehavior();
});

/**
 * Swipe Gestures for Sidebar
 */
function initSwipeGestures() {
    let startX = 0;
    let startY = 0;
    let isSwipe = false;
    const threshold = 50; // Minimum distance for swipe
    const sidebar = document.querySelector('.sidebar-mobile');
    const overlay = document.querySelector('.fixed.inset-0.z-40');

    // Touch start
    document.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isSwipe = false;
    }, { passive: true });

    // Touch move
    document.addEventListener('touchmove', function(e) {
        if (!startX || !startY) return;

        const currentX = e.touches[0].clientX;
        const currentY = e.touches[0].clientY;
        const diffX = startX - currentX;
        const diffY = startY - currentY;

        // Determine if this is a horizontal swipe
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > threshold) {
            isSwipe = true;
            // Don't prevent default - let the browser handle scrolling naturally
        }
    }, { passive: true });

    // Touch end
    document.addEventListener('touchend', function(e) {
        if (!isSwipe || !startX || !startY) return;

        const currentX = e.changedTouches[0].clientX;
        const diffX = startX - currentX;
        const isLeftSwipe = diffX > threshold;
        const isRightSwipe = diffX < -threshold;

        // Only handle swipes from screen edges
        if (startX < 50 && isRightSwipe) {
            // Swipe right from left edge - open sidebar
            openSidebar();
        } else if (isLeftSwipe && sidebar && !sidebar.classList.contains('hidden')) {
            // Swipe left - close sidebar
            closeSidebar();
        }

        // Reset
        startX = 0;
        startY = 0;
        isSwipe = false;
    }, { passive: true });
}

/**
 * Mobile Modal Optimizations
 */
function initMobileModals() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    
    modals.forEach(modal => {
        // Make modals full-screen on mobile
        if (window.innerWidth < 768) {
            modal.classList.add('mobile-fullscreen');
        }

        // Handle modal close on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(modal);
            }
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal(modal);
            }
        });
    });
}


/**
 * Orientation Change Handling
 */
function initOrientationHandling() {
    let orientationTimeout;
    
    window.addEventListener('orientationchange', function() {
        // Debounce orientation changes
        clearTimeout(orientationTimeout);
        orientationTimeout = setTimeout(() => {
            // Recalculate layouts
            adjustLayoutForOrientation();
        }, 100);
    });

    // Also handle resize events
    window.addEventListener('resize', function() {
        clearTimeout(orientationTimeout);
        orientationTimeout = setTimeout(() => {
            adjustLayoutForOrientation();
        }, 100);
    });
}

/**
 * FAB (Floating Action Button) Behavior
 */
function initFABBehavior() {
    const fab = document.querySelector('.fixed.bottom-6.right-6');
    if (!fab) return;

    let lastScrollY = window.scrollY;
    let isScrolling = false;

    window.addEventListener('scroll', function() {
        if (!isScrolling) {
            requestAnimationFrame(() => {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    // Scrolling down - hide FAB
                    fab.style.transform = 'translateY(100px)';
                    fab.style.opacity = '0';
                } else {
                    // Scrolling up - show FAB
                    fab.style.transform = 'translateY(0)';
                    fab.style.opacity = '1';
                }
                
                lastScrollY = currentScrollY;
                isScrolling = false;
            });
            isScrolling = true;
        }
    });
}

/**
 * Utility Functions
 */
function openSidebar() {
    const sidebar = document.querySelector('.sidebar-mobile');
    const overlay = document.querySelector('.fixed.inset-0.z-40');
    
    if (sidebar && window.innerWidth < 1024) {
        sidebar.classList.remove('hidden');
        if (overlay) overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar-mobile');
    const overlay = document.querySelector('.fixed.inset-0.z-40');
    
    if (sidebar) {
        sidebar.classList.add('hidden');
        if (overlay) overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function closeModal(modal) {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function adjustLayoutForOrientation() {
    // Adjust any layout-specific elements based on orientation
    const isLandscape = window.innerWidth > window.innerHeight;
    
    // Example: Adjust card layouts for landscape
    const cards = document.querySelectorAll('.mobile-card');
    cards.forEach(card => {
        if (isLandscape && window.innerWidth < 1024) {
            card.classList.add('landscape-layout');
        } else {
            card.classList.remove('landscape-layout');
        }
    });
}


/**
 * Touch Ripple Effect
 */
function addRippleEffect(element) {
    element.addEventListener('touchstart', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.touches[0].clientX - rect.left - size / 2;
        const y = e.touches[0].clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }, { passive: true });
}

// Add ripple effect to all buttons
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('button, .btn, .touch-target');
    buttons.forEach(addRippleEffect);
});

/**
 * Mobile Filters Toggle Function
 * Global function to toggle mobile filter visibility
 */
function toggleMobileFilters() {
    const filters = document.getElementById('mobile-filters');
    const arrow = document.getElementById('filter-arrow');
    
    if (filters && arrow) {
        if (filters.classList.contains('hidden')) {
            filters.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            filters.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
}

// Make the function globally available
window.toggleMobileFilters = toggleMobileFilters;

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .mobile-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        max-width: none !important;
        max-height: none !important;
        border-radius: 0 !important;
    }
    
    .landscape-layout {
        flex-direction: row !important;
    }
    
    .refreshing {
        animation: spin 1s linear infinite;
    }
`;
document.head.appendChild(style);
