<!-- Language Switcher Component -->
<div class="relative" x-data="{ open: false, isLoading: false }" data-language-switcher>
    <button @click.stop="open = !open" 
            :disabled="isLoading"
            :class="{ 'language-switcher-loading': isLoading }"
            class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 border border-gray-200 rounded-xl hover:from-blue-100 hover:to-purple-100 language-switch-btn shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
        <span x-text="language === 'fr' ? '🇫🇷 FR' : '🇸🇦 AR'" class="font-semibold"></span>
        <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
        <svg x-show="isLoading" class="ml-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
    </button>
    
    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-3 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden language-dropdown">
        <button data-language-switch="fr" 
                @click.stop="isLoading = true; $nextTick(() => { if (window.languageSwitcher) { window.languageSwitcher.switchLanguage('fr').finally(() => isLoading = false); } }); open = false"
                :disabled="isLoading"
                class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 disabled:opacity-50">
            <span class="text-lg mr-3">🇫🇷</span>
            <span class="font-medium">Français</span>
        </button>
        
        <button data-language-switch="ar" 
                @click.stop="isLoading = true; $nextTick(() => { if (window.languageSwitcher) { window.languageSwitcher.switchLanguage('ar').finally(() => isLoading = false); } }); open = false"
                :disabled="isLoading"
                class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 disabled:opacity-50">
            <span class="text-lg mr-3">🇸🇦</span>
            <span class="font-medium">العربية</span>
        </button>
    </div>
</div>
