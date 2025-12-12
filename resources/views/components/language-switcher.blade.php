<!-- Language Switcher Component -->
<div class="relative" 
     x-data="{
         open: false, 
         isLoading: false,
         language: document.documentElement.lang || 'fr',
         loadingTimeout: null,
         init() {
             // Ensure dropdown is closed on init
             this.open = false;
             this.isLoading = false;
             
             // Get initial language
             this.language = document.documentElement.lang || 'fr';
             
             // Safety: ensure loading state doesn't get stuck
             this.$watch('isLoading', (value) => {
                 if (value) {
                     // Clear any existing timeout
                     if (this.loadingTimeout) clearTimeout(this.loadingTimeout);
                     // Set safety timeout to clear loading after 5 seconds
                     this.loadingTimeout = setTimeout(() => {
                         this.isLoading = false;
                         console.warn('Language switcher: Loading timeout - clearing loading state');
                     }, 5000);
                 } else {
                     if (this.loadingTimeout) {
                         clearTimeout(this.loadingTimeout);
                         this.loadingTimeout = null;
                     }
                 }
             });
             
             // Ensure dropdown closes if it somehow opens on its own
             this.$watch('open', (value) => {
                 if (value && this.isLoading) {
                     // Don't allow opening while loading
                     setTimeout(() => { this.open = false; }, 100);
                 }
             });
         }
     }" 
     data-language-switcher>
</div>
