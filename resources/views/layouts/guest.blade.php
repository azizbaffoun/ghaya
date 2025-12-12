<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans text-gray-900 antialiased" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-gray-100">
            
            <div class="relative z-10 flex justify-between items-center w-full max-w-md px-6 mb-8">
                <a href="/" class="transition-opacity duration-200 hover:opacity-80">
                    <x-application-logo class="w-16 h-16 fill-current text-gray-800" />
                </a>
                <!-- Language Switcher -->
                <div class="relative z-20">
                    @include('components.language-switcher')
                </div>
            </div>

            <div class="relative z-10 w-full sm:max-w-md px-8 py-12 bg-white shadow-lg overflow-hidden sm:rounded-2xl border border-gray-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
