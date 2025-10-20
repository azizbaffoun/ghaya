/**
 * Language Switcher Service
 * Handles smooth language switching without page reloads
 */
class LanguageSwitcher {
    constructor() {
        this.currentLocale = document.documentElement.lang || 'fr';
        this.translations = {};
        this.init();
    }

    init() {
        // Don't load translations on page load - only when needed
        // this.loadTranslations(this.currentLocale);
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Initialize Alpine.js data
        this.initializeAlpineData();
    }

    setupEventListeners() {
        // Listen for language switch clicks only within the language switcher component
        const languageSwitcher = document.querySelector('[data-language-switcher]');
        if (languageSwitcher) {
            languageSwitcher.addEventListener('click', (e) => {
                if (e.target.closest('[data-language-switch]')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const locale = e.target.closest('[data-language-switch]').dataset.languageSwitch;
                    this.switchLanguage(locale);
                }
            });
        }
    }

    initializeAlpineData() {
        // Update Alpine.js data if available
        if (window.Alpine) {
            document.addEventListener('alpine:init', () => {
                Alpine.data('languageSwitcher', () => ({
                    currentLanguage: this.currentLocale,
                    isLoading: false,
                    
                    switchLanguage(locale) {
                        this.isLoading = true;
                        this.switchLanguageAsync(locale);
                    },
                    
                    async switchLanguageAsync(locale) {
                        try {
                            await LanguageSwitcher.instance.switchLanguage(locale);
                            this.currentLanguage = locale;
                        } catch (error) {
                            console.error('Language switch failed:', error);
                        } finally {
                            this.isLoading = false;
                        }
                    }
                }));
            });
        }
    }

    async loadTranslations(locale) {
        try {
            const response = await fetch('/language/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ locale: locale })
            });

            if (!response.ok) {
                throw new Error('Failed to load translations');
            }

            const data = await response.json();
            if (data.success) {
                this.translations = data.translations;
                this.currentLocale = data.locale;
                this.updateUI();
            }
        } catch (error) {
            console.error('Error loading translations:', error);
        }
    }

    async switchLanguage(locale) {
        if (locale === this.currentLocale) return;

        // Show loading state
        this.showLoadingState();

        try {
            const response = await fetch('/language/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ locale: locale })
            });

            if (!response.ok) {
                throw new Error('Failed to switch language');
            }

            const data = await response.json();
            if (data.success) {
                this.translations = data.translations;
                this.currentLocale = data.locale;
                this.updateUI();
                this.updateDocumentAttributes();
                this.showSuccessMessage(data.message);
                
                // Update content dynamically instead of reloading
                // setTimeout(() => {
                //     window.location.reload();
                // }, 1000);
            } else {
                throw new Error(data.message || 'Language switch failed');
            }
        } catch (error) {
            console.error('Error switching language:', error);
            this.showErrorMessage('Failed to switch language');
        } finally {
            this.hideLoadingState();
        }
    }

    updateUI() {
        // Add smooth transition class to elements being updated
        document.querySelectorAll('[data-translate]').forEach(element => {
            element.classList.add('translation-text', 'updating');
        });

        // Use requestAnimationFrame for smooth updates
        requestAnimationFrame(() => {
            // Update all elements with data-translate attribute
            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.dataset.translate;
                if (this.translations[key]) {
                    element.textContent = this.translations[key];
                }
                // Remove updating class after a short delay
                setTimeout(() => {
                    element.classList.remove('updating');
                }, 100);
            });

            // Update sidebar navigation items
            this.updateSidebarTranslations();
            
            // Update page title if it has a translation key
            const titleElement = document.querySelector('[data-translate-title]');
            if (titleElement && this.translations[titleElement.dataset.translateTitle]) {
                document.title = this.translations[titleElement.dataset.translateTitle];
            }
        });
    }

    updateSidebarTranslations() {
        // Update sidebar navigation items
        const sidebarItems = {
            'admin.dashboard.title': document.querySelector('a[href*="dashboard"]'),
            'admin.products.title': document.querySelector('a[href*="products"]'),
            'admin.categories.title': document.querySelector('a[href*="categories"]'),
            'admin.orders.title': document.querySelector('a[href*="orders"]'),
            'admin.customers.title': document.querySelector('a[href*="customers"]'),
            'admin.banners.title': document.querySelector('a[href*="banners"]'),
            'admin.languages.title': document.querySelector('a[href*="languages"]'),
        };

        Object.entries(sidebarItems).forEach(([key, element]) => {
            if (element && this.translations[key]) {
                // Find the span with data-translate attribute
                const spanElement = element.querySelector('[data-translate]');
                if (spanElement) {
                    spanElement.classList.add('translation-text', 'updating');
                    spanElement.textContent = this.translations[key];
                    setTimeout(() => {
                        spanElement.classList.remove('updating');
                    }, 100);
                }
            }
        });
    }

    updateDocumentAttributes() {
        // Add transition class for smooth direction change
        document.body.classList.add('rtl-transition');
        
        // Update document language and direction
        document.documentElement.lang = this.currentLocale;
        document.documentElement.dir = this.currentLocale === 'ar' ? 'rtl' : 'ltr';
        
        // Update body class for RTL/LTR
        document.body.classList.remove('rtl', 'ltr');
        document.body.classList.add(this.currentLocale === 'ar' ? 'rtl' : 'ltr');
        
        // Remove transition class after animation
        setTimeout(() => {
            document.body.classList.remove('rtl-transition');
        }, 300);
    }

    showLoadingState() {
        // Add loading class to language switcher
        const switcher = document.querySelector('[data-language-switcher]');
        if (switcher) {
            switcher.classList.add('loading-state');
        }
    }

    hideLoadingState() {
        // Remove loading class from language switcher
        const switcher = document.querySelector('[data-language-switcher]');
        if (switcher) {
            switcher.classList.remove('loading-state');
        }
    }

    showSuccessMessage(message) {
        // You can implement a toast notification here
        console.log('Success:', message);
    }

    showErrorMessage(message) {
        // You can implement a toast notification here
        console.error('Error:', message);
        alert(message); // Fallback to alert for now
    }
}

// Initialize the language switcher
document.addEventListener('DOMContentLoaded', () => {
    // Prevent multiple initializations
    if (window.languageSwitcher) {
        return;
    }
    window.languageSwitcher = new LanguageSwitcher();
    LanguageSwitcher.instance = window.languageSwitcher;
});

// Export for use in other modules
window.LanguageSwitcher = LanguageSwitcher;
