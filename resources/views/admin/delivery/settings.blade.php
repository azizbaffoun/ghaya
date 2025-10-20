@extends('admin.layout')

@section('title', __('admin.delivery.delivery_settings'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin.delivery.delivery_settings') }}</h1>
            <p class="text-gray-600">{{ __('admin.delivery.delivery_settings_subtitle') }}</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Settings Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('admin.delivery.settings.save') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Enable First Delivery Toggle -->
                <div class="flex items-center justify-between p-6 bg-gray-50 rounded-xl">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.delivery.first_delivery') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.delivery.enable_integration') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_enabled" value="1" 
                               {{ old('is_enabled', $settings->is_enabled) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- API Configuration -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ __('admin.delivery.api_configuration') }}</h2>
                    
                    <div>
                        <label for="first_delivery_key" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.delivery.api_key') }} *
                        </label>
                        <input type="password" 
                               name="first_delivery_key" 
                               id="first_delivery_key"
                               value="{{ old('first_delivery_key', $settings->first_delivery_key) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_delivery_key') border-red-500 @enderror"
                               placeholder="{{ __('admin.delivery.api_key_placeholder') }}">
                        @error('first_delivery_key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Cost Configuration -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Cost Configuration</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="delivery_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.delivery.delivery_cost_label') }} *
                            </label>
                            <input type="number" 
                                   name="delivery_cost" 
                                   id="delivery_cost"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('delivery_cost', $settings->delivery_cost) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('delivery_cost') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('delivery_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="return_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.delivery.return_cost_label') }} *
                            </label>
                            <input type="number" 
                                   name="return_cost" 
                                   id="return_cost"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('return_cost', $settings->return_cost) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('return_cost') border-red-500 @enderror"
                                   placeholder="0.00">
                            @error('return_cost')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Store Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Store Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Store Name *
                            </label>
                            <input type="text" 
                                   name="store_name" 
                                   id="store_name"
                                   value="{{ old('store_name', $settings->store_name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_name') border-red-500 @enderror"
                                   placeholder="Your Store Name">
                            @error('store_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Store Phone *
                            </label>
                            <input type="tel" 
                                   name="store_phone" 
                                   id="store_phone"
                                   value="{{ old('store_phone', $settings->store_phone) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_phone') border-red-500 @enderror"
                                   placeholder="+216 XX XXX XXX">
                            @error('store_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="store_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Address *
                        </label>
                        <textarea name="store_address" 
                                  id="store_address"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_address') border-red-500 @enderror"
                                  placeholder="Enter your store's full address">{{ old('store_address', $settings->store_address) }}</textarea>
                        @error('store_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="store_city" class="block text-sm font-medium text-gray-700 mb-2">
                                City *
                            </label>
                            <select name="store_city" 
                                    id="store_city"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('store_city') border-red-500 @enderror">
                                <option value="">Select City</option>
                                <option value="Tunis" {{ old('store_city', $settings->store_city) == 'Tunis' ? 'selected' : '' }}>Tunis</option>
                                <option value="Sfax" {{ old('store_city', $settings->store_city) == 'Sfax' ? 'selected' : '' }}>Sfax</option>
                                <option value="Sousse" {{ old('store_city', $settings->store_city) == 'Sousse' ? 'selected' : '' }}>Sousse</option>
                                <option value="Kairouan" {{ old('store_city', $settings->store_city) == 'Kairouan' ? 'selected' : '' }}>Kairouan</option>
                                <option value="Bizerte" {{ old('store_city', $settings->store_city) == 'Bizerte' ? 'selected' : '' }}>Bizerte</option>
                                <option value="Gabès" {{ old('store_city', $settings->store_city) == 'Gabès' ? 'selected' : '' }}>Gabès</option>
                                <option value="Ariana" {{ old('store_city', $settings->store_city) == 'Ariana' ? 'selected' : '' }}>Ariana</option>
                                <option value="Gafsa" {{ old('store_city', $settings->store_city) == 'Gafsa' ? 'selected' : '' }}>Gafsa</option>
                                <option value="Monastir" {{ old('store_city', $settings->store_city) == 'Monastir' ? 'selected' : '' }}>Monastir</option>
                                <option value="Ben Arous" {{ old('store_city', $settings->store_city) == 'Ben Arous' ? 'selected' : '' }}>Ben Arous</option>
                                <option value="Kasserine" {{ old('store_city', $settings->store_city) == 'Kasserine' ? 'selected' : '' }}>Kasserine</option>
                                <option value="Medenine" {{ old('store_city', $settings->store_city) == 'Medenine' ? 'selected' : '' }}>Medenine</option>
                                <option value="Nabeul" {{ old('store_city', $settings->store_city) == 'Nabeul' ? 'selected' : '' }}>Nabeul</option>
                                <option value="Tataouine" {{ old('store_city', $settings->store_city) == 'Tataouine' ? 'selected' : '' }}>Tataouine</option>
                                <option value="Béja" {{ old('store_city', $settings->store_city) == 'Béja' ? 'selected' : '' }}>Béja</option>
                                <option value="Jendouba" {{ old('store_city', $settings->store_city) == 'Jendouba' ? 'selected' : '' }}>Jendouba</option>
                                <option value="Kébili" {{ old('store_city', $settings->store_city) == 'Kébili' ? 'selected' : '' }}>Kébili</option>
                                <option value="Kef" {{ old('store_city', $settings->store_city) == 'Kef' ? 'selected' : '' }}>Kef</option>
                                <option value="Mahdia" {{ old('store_city', $settings->store_city) == 'Mahdia' ? 'selected' : '' }}>Mahdia</option>
                                <option value="Manouba" {{ old('store_city', $settings->store_city) == 'Manouba' ? 'selected' : '' }}>Manouba</option>
                                <option value="Médenine" {{ old('store_city', $settings->store_city) == 'Médenine' ? 'selected' : '' }}>Médenine</option>
                                <option value="Siliana" {{ old('store_city', $settings->store_city) == 'Siliana' ? 'selected' : '' }}>Siliana</option>
                                <option value="Sidi Bouzid" {{ old('store_city', $settings->store_city) == 'Sidi Bouzid' ? 'selected' : '' }}>Sidi Bouzid</option>
                                <option value="Zaghouan" {{ old('store_city', $settings->store_city) == 'Zaghouan' ? 'selected' : '' }}>Zaghouan</option>
                                <option value="Tozeur" {{ old('store_city', $settings->store_city) == 'Tozeur' ? 'selected' : '' }}>Tozeur</option>
                            </select>
                            @error('store_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-2">
                                VAT Number
                            </label>
                            <input type="text" 
                                   name="vat_number" 
                                   id="vat_number"
                                   value="{{ old('vat_number', $settings->vat_number) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vat_number') border-red-500 @enderror"
                                   placeholder="VAT Number (optional)">
                            @error('vat_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Package Options -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Package Options</h2>
                    
                    <div class="flex items-center justify-between p-6 bg-gray-50 rounded-xl">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Allow to open package</h3>
                            <p class="text-sm text-gray-600">Allow customers to open packages before payment</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_open_package" value="1" 
                                   {{ old('allow_open_package', $settings->allow_open_package) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



