console.log("Live search script loaded");class n{constructor(){this.searchInput=null,this.resultsContainer=null,this.searchTimeout=null,this.isSearching=!1,this.currentFilters={},this.debounceDelay=800,this.init()}init(){this.isDashboardPage()&&(document.readyState==="loading"?document.addEventListener("DOMContentLoaded",()=>this.setupEventListeners()):(this.setupEventListeners(),this.searchInput||setTimeout(()=>this.setupEventListeners(),100)))}isDashboardPage(){const t=window.location.pathname,e=t.includes("/admin/dashboard")||t==="/admin"||t==="/admin/",s=document.querySelector("#orders-results-container")!==null;return console.log("Current path:",t,"Is dashboard:",e,"Has dashboard elements:",s),e||s}setupEventListeners(){this.searchInput=document.querySelector('input[name="search"]'),this.resultsContainer=document.querySelector("#orders-results-container"),!(!this.searchInput&&(this.searchInput=document.querySelector('input[type="text"][placeholder*="Order"]'),!this.searchInput))&&this.resultsContainer&&(this.searchInput.addEventListener("input",t=>{this.handleSearchInput(t)}),this.searchInput.addEventListener("keydown",t=>this.handleKeyDown(t)),this.setupClearSearchButton(),this.setupFilterListeners(),this.addLoadingIndicator())}setupClearSearchButton(){const t=document.querySelector("#clear-search");t&&(t.addEventListener("click",()=>{console.log("Clear search clicked"),this.searchInput.value="",this.showAllResults(),this.toggleClearButton(!1)}),this.searchInput.addEventListener("input",()=>{this.toggleClearButton(this.searchInput.value.length>0)}),this.toggleClearButton(this.searchInput.value.length>0))}toggleClearButton(t){const e=document.querySelector("#clear-search");e&&(t?e.classList.remove("hidden"):e.classList.add("hidden"))}setupFilterListeners(){const t=document.querySelector('select[name="status"]');t&&t.addEventListener("change",()=>{console.log("Status filter changed"),this.updateFilters()});const e=document.querySelector('select[name="confirmation_status"]');e&&e.addEventListener("change",()=>{console.log("Confirmation status filter changed"),this.updateFilters()});const s=document.querySelector('input[name="date_from"]');s&&s.addEventListener("change",()=>{console.log("Date from filter changed"),this.updateFilters()});const a=document.querySelector('input[name="date_to"]');a&&a.addEventListener("change",()=>{console.log("Date to filter changed"),this.updateFilters()})}handleSearchInput(t){const e=t.target.value.trim();if(t.preventDefault(),this.searchTimeout&&clearTimeout(this.searchTimeout),e===""){this.showAllResults();return}e.length<2||(this.setLoadingState(!0),this.searchTimeout=setTimeout(()=>{this.performSearch(e)},this.debounceDelay))}handleKeyDown(t){t.key==="Enter"&&(t.preventDefault(),this.performSearch(this.searchInput.value.trim())),t.key==="Escape"&&(this.searchInput.value="",this.showAllResults())}updateFilters(){var e,s,a,r;this.currentFilters={status:((e=document.querySelector('select[name="status"]'))==null?void 0:e.value)||"",confirmation_status:((s=document.querySelector('select[name="confirmation_status"]'))==null?void 0:s.value)||"",date_from:((a=document.querySelector('input[name="date_from"]'))==null?void 0:a.value)||"",date_to:((r=document.querySelector('input[name="date_to"]'))==null?void 0:r.value)||""};const t=this.searchInput.value.trim();t?this.performSearch(t):this.showAllResults()}async performSearch(t){var e;if(!this.isSearching){this.isSearching=!0,this.setLoadingState(!0);try{const s=new URLSearchParams({search:t,...this.currentFilters}),a=await fetch(`/admin/orders/search?${s}`,{method:"GET",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":((e=document.querySelector('meta[name="csrf-token"]'))==null?void 0:e.getAttribute("content"))||"",Accept:"application/json"}});if(!a.ok)throw new Error(`HTTP error! status: ${a.status}`);const r=await a.json();if(r.success)this.displaySearchResults(r.data);else throw new Error(r.message||"Search failed")}catch(s){console.error("Search error:",s),this.showError("Search failed. Please try again.")}finally{this.isSearching=!1,this.setLoadingState(!1)}}}displaySearchResults(t){this.resultsContainer&&(this.updateOrdersTable(t),this.showResultsCount(t.length))}updateOrdersTable(t){const e=document.querySelector("tbody"),s=document.querySelector(".lg\\:hidden.space-y-4.p-4");!e&&!s||(e&&(e.innerHTML=this.generateDesktopTableRows(t)),s&&(s.innerHTML=this.generateMobileCards(t)),this.attachOrderEventListeners())}generateDesktopTableRows(t){return t.length===0?`
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria</p>
                    </td>
                </tr>
            `:t.map(e=>{const a={pending:"bg-yellow-100 text-yellow-800",confirmed:"bg-green-100 text-green-800",declined:"bg-red-100 text-red-800",processing:"bg-blue-100 text-blue-800",shipped:"bg-purple-100 text-purple-800",delivered:"bg-green-100 text-green-800",cancelled:"bg-gray-100 text-gray-800"}[e.status]||"bg-gray-100 text-gray-800",r=typeof e.shipping_address=="string"?e.shipping_address:JSON.stringify(e.shipping_address);return`
                <tr class="hover:bg-gray-50 cursor-pointer" 
                    onclick="openQuickView(${JSON.stringify(e).replace(/"/g,"&quot;")})">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span class="text-indigo-600 hover:text-indigo-900">
                            ${e.order_number}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${e.customer_first_name||""}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${e.customer_last_name||""}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${this.truncateText(r,30)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${e.customer_phone||"N/A"}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${a}">
                            ${this.getStatusText(e.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${e.created_at}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${parseFloat(e.total).toFixed(2)} د.م
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="event.stopPropagation(); openQuickView(${JSON.stringify(e).replace(/"/g,"&quot;")})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            View
                        </button>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            Edit
                        </a>
                    </td>
                </tr>
            `}).join("")}generateMobileCards(t){return t.length===0?`
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria</p>
                </div>
            `:t.map(e=>{const a={pending:"bg-yellow-100 text-yellow-800",confirmed:"bg-green-100 text-green-800",declined:"bg-red-100 text-red-800",processing:"bg-blue-100 text-blue-800",shipped:"bg-purple-100 text-purple-800",delivered:"bg-green-100 text-green-800",cancelled:"bg-gray-100 text-gray-800"}[e.status]||"bg-gray-100 text-gray-800";return`
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">#${e.order_number}</h4>
                            <p class="text-sm text-gray-500">${e.created_at}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${a}">
                            ${this.getStatusText(e.status)}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Customer</span>
                            <span class="text-sm font-medium text-gray-900">${e.customer_first_name} ${e.customer_last_name}</span>
                        </div>
                        ${e.customer_phone?`
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Phone</span>
                            <a href="tel:${e.customer_phone}" class="text-sm text-blue-600">${e.customer_phone}</a>
                        </div>
                        `:""}
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total</span>
                            <span class="text-sm font-bold text-gray-900">${parseFloat(e.total).toFixed(2)} د.م</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button onclick="openQuickView(${JSON.stringify(e).replace(/"/g,"&quot;")})" 
                                class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            View
                        </button>
                        <a href="#" class="flex-1 bg-gray-200 text-gray-800 text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                            Edit
                        </a>
                    </div>
                </div>
            `}).join("")}attachOrderEventListeners(){}showAllResults(){console.log("Showing all results (updating dynamically)")}showResultsCount(t){const e=document.querySelector("#results-count");e&&(t>0?(e.textContent=`${t} results found`,e.classList.remove("hidden")):e.classList.add("hidden"))}setLoadingState(t){const e=this.searchInput,s=document.querySelector("#search-loading");e&&(t?(e.classList.add("opacity-50"),e.disabled=!0,s&&s.classList.remove("hidden")):(e.classList.remove("opacity-50"),e.disabled=!1,s&&s.classList.add("hidden")))}addLoadingIndicator(){var s;const t=(s=this.searchInput)==null?void 0:s.parentElement;if(!t)return;const e=document.createElement("div");e.id="search-loading",e.className="hidden absolute right-3 top-1/2 transform -translate-y-1/2",e.innerHTML=`
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `,t.style.position="relative",t.appendChild(e)}showError(t){console.error("Showing error:",t);const e=document.createElement("div");e.className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4",e.textContent=t;const s=this.resultsContainer||document.querySelector(".space-y-6");s&&(s.insertBefore(e,s.firstChild),setTimeout(()=>{e.remove()},5e3))}truncateText(t,e){return t.length<=e?t:t.substring(0,e)+"..."}getStatusText(t){return{pending:"Pending",confirmed:"Confirmed",declined:"Declined",processing:"Processing",shipped:"Shipped",delivered:"Delivered",cancelled:"Cancelled"}[t]||t}}document.addEventListener("DOMContentLoaded",function(){window.liveSearchInstance||(window.liveSearchInstance=new n)});window.LiveSearch=n;
