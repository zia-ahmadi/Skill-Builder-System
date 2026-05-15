<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui'] },
                    },
                },
            };
        </script>
        <style>
            /* Dark Theme */
            body.theme-dark {
                background: radial-gradient(circle at 20% 20%, rgba(99,102,241,0.15), transparent 30%),
                            radial-gradient(circle at 80% 0%, rgba(14,165,233,0.18), transparent 32%),
                            linear-gradient(135deg, #0f172a, #0b1220 40%, #0b1024);
                color: #e2e8f0;
            }
            
            /* Light Theme with Brand Colors - Enhanced */
            body.theme-light {
                background: linear-gradient(135deg, #F5F6F8 0%, #FFFFFF 50%, #F5F6F8 100%);
                background-attachment: fixed;
                color: #1F2933;
            }
            
            /* Light Theme - Navigation & Header */
            body.theme-light nav {
                background-color: #FFFFFF !important;
                border-color: #e5e7eb !important;
            }
            body.theme-light nav .text-indigo-300 {
                color: #0A2540 !important;
            }
            body.theme-light nav .text-white {
                color: #1F2933 !important;
            }
            body.theme-light nav .text-slate-200,
            body.theme-light nav .text-slate-300 {
                color: #1F2933 !important;
            }
            body.theme-light nav .bg-slate-800 {
                background-color: #F5F6F8 !important;
            }
            body.theme-light nav .hover\:bg-slate-800:hover {
                background-color: #F5F6F8 !important;
            }
            body.theme-light nav .hover\:text-white:hover {
                color: #0A2540 !important;
            }
            body.theme-light nav .bg-indigo-600 {
                background-color: #0A2540 !important;
            }
            body.theme-light nav .hover\:bg-indigo-600:hover {
                background-color: #0C2F52 !important;
            }
            body.theme-light nav .hover\:bg-indigo-500:hover {
                background-color: #0C2F52 !important;
            }
            body.theme-light header {
                background-color: #FFFFFF !important;
                border-color: #e5e7eb !important;
            }
            
            /* Light Theme - Text Colors - Enhanced */
            body.theme-light .text-white,
            body.theme-light .text-slate-100,
            body.theme-light .text-gray-100 { color: #1F2933 !important; }
            body.theme-light .text-slate-200,
            body.theme-light .text-gray-200 { color: #374151 !important; }
            body.theme-light .text-slate-300,
            body.theme-light .text-gray-300 { color: #1F2933 !important; }
            body.theme-light .text-slate-400,
            body.theme-light .text-gray-400 { color: #6B7280 !important; }
            body.theme-light .text-slate-500,
            body.theme-light .text-gray-500 { color: #6B7280 !important; }
            body.theme-light .text-gray-100 { color: #1F2933 !important; }
            body.theme-light .text-gray-200 { color: #374151 !important; }
            body.theme-light .text-gray-300 { color: #1F2933 !important; }
            body.theme-light .text-gray-400 { color: #6B7280 !important; }
            body.theme-light header .text-gray-100 { color: #1F2933 !important; }
            body.theme-light header .text-gray-400 { color: #6B7280 !important; }
            
            /* Light Theme - Background Colors - Enhanced */
            body.theme-light .bg-slate-900\/70,
            body.theme-light .bg-slate-900\/80,
            body.theme-light .bg-slate-900\/90,
            body.theme-light .bg-slate-900\/60,
            body.theme-light .bg-slate-900\/50 {
                background-color: #FFFFFF !important;
                color: #1F2933 !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
            }
            body.theme-light .bg-slate-900 {
                background-color: #FFFFFF !important;
            }
            body.theme-light .bg-slate-800,
            body.theme-light .bg-gray-800 {
                background-color: #F5F6F8 !important;
                color: #1F2933 !important;
            }
            body.theme-light .bg-slate-800\/50,
            body.theme-light .bg-slate-800\/60,
            body.theme-light .bg-slate-800\/80,
            body.theme-light .bg-slate-800\/40,
            body.theme-light .bg-slate-800\/30 {
                background-color: #F5F6F8 !important;
            }
            body.theme-light .bg-gray-100\/0 {
                background-color: transparent !important;
            }
            
            /* Light Theme - Borders - Enhanced */
            body.theme-light .border-slate-800,
            body.theme-light .border-slate-700,
            body.theme-light .border-gray-700,
            body.theme-light .border-gray-800 {
                border-color: #e5e7eb !important;
            }
            body.theme-light .border-slate-800\/50,
            body.theme-light .border-slate-700\/50,
            body.theme-light .border-slate-800\/40,
            body.theme-light .border-slate-700\/40,
            body.theme-light .border-slate-700\/30,
            body.theme-light .border-slate-800\/30,
            body.theme-light .border-slate-700\/60 {
                border-color: #e5e7eb !important;
            }
            body.theme-light .border-gray-200 {
                border-color: #e5e7eb !important;
            }
            
            /* Light Theme - Brand Colors */
            body.theme-light .text-indigo-300,
            body.theme-light .text-indigo-400 { color: #0A2540 !important; }
            body.theme-light .bg-indigo-600,
            body.theme-light .bg-indigo-500 {
                background-color: #0A2540 !important;
                color: #FFFFFF !important;
            }
            body.theme-light .hover\:bg-indigo-500:hover,
            body.theme-light .hover\:bg-indigo-600:hover {
                background-color: #0C2F52 !important;
            }
            
            /* Light Theme - Gold Accent */
            body.theme-light .bg-amber-500\/20,
            body.theme-light .bg-amber-500\/30 {
                background-color: rgba(201, 162, 77, 0.2) !important;
            }
            body.theme-light .text-amber-300,
            body.theme-light .text-amber-400 { color: #C9A24D !important; }
            
            /* Light Theme - Inputs */
            body.theme-light input,
            body.theme-light textarea,
            body.theme-light select {
                background-color: #FFFFFF !important;
                color: #1F2933 !important;
                border-color: #e5e7eb !important;
            }
            body.theme-light .bg-slate-800\/80 input,
            body.theme-light .bg-slate-800\/80 textarea,
            body.theme-light .bg-slate-800\/80 select {
                background-color: #FFFFFF !important;
            }
            
            /* Light Theme - Hover States - Enhanced */
            body.theme-light .hover\:bg-slate-800:hover,
            body.theme-light .hover\:bg-slate-700:hover {
                background-color: #F5F6F8 !important;
                box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
            }
            body.theme-light .hover\:bg-slate-900\/80:hover,
            body.theme-light .hover\:bg-slate-900\/60:hover {
                background-color: #F9FAFB !important;
            }
            body.theme-light .hover\:text-white:hover {
                color: #0A2540 !important;
            }
            body.theme-light .hover\:text-indigo-300:hover,
            body.theme-light .hover\:text-indigo-200:hover {
                color: #0A2540 !important;
            }
            body.theme-light .hover\:border-indigo-500\/30:hover {
                border-color: rgba(10, 37, 64, 0.3) !important;
            }
            body.theme-light .hover\:shadow-xl:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            }
            
            /* Light Theme - Gradient Cards - Enhanced */
            body.theme-light .bg-gradient-to-br.from-slate-900\/90.to-slate-800\/90,
            body.theme-light .bg-gradient-to-br.from-slate-900\/70.to-slate-800\/90,
            body.theme-light .bg-gradient-to-br.from-slate-900\/80.to-slate-800\/90 {
                background: linear-gradient(to bottom right, #FFFFFF, #F5F6F8) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            }
            body.theme-light .bg-gradient-to-r.from-indigo-600.to-purple-600,
            body.theme-light .bg-gradient-to-r.from-indigo-600\/20.to-purple-600\/20 {
                background: linear-gradient(to right, #0A2540, #0C2F52) !important;
                box-shadow: 0 4px 6px -1px rgba(10, 37, 64, 0.2), 0 2px 4px -1px rgba(10, 37, 64, 0.1) !important;
            }
            body.theme-light .bg-gradient-to-r.from-indigo-600\/25.to-purple-600\/25,
            body.theme-light .bg-gradient-to-br.from-indigo-600\/25.via-purple-600\/25.to-indigo-700\/25 {
                background: linear-gradient(to bottom right, rgba(10, 37, 64, 0.08), rgba(12, 47, 82, 0.08)) !important;
                border-color: rgba(10, 37, 64, 0.15) !important;
            }
            body.theme-light .bg-gradient-to-br.from-violet-600\/30.via-purple-600\/30.to-fuchsia-600\/30 {
                background: linear-gradient(to bottom right, rgba(10, 37, 64, 0.08), rgba(12, 47, 82, 0.08), rgba(10, 37, 64, 0.08)) !important;
                border-color: rgba(10, 37, 64, 0.15) !important;
            }
            body.theme-light .bg-gradient-to-r.from-violet-600.via-purple-600.to-fuchsia-600 {
                background: linear-gradient(to right, #0A2540, #0C2F52, #0A2540) !important;
            }
            body.theme-light .bg-gradient-to-r.from-violet-500.via-purple-500.to-fuchsia-500 {
                background: linear-gradient(to right, #0A2540, #0C2F52, #0A2540) !important;
            }
            
            /* Light Theme - Success Color */
            body.theme-light .text-green-400,
            body.theme-light .text-emerald-300 { color: #14532D !important; }
            body.theme-light .bg-green-500\/20,
            body.theme-light .bg-emerald-500\/30 {
                background-color: rgba(20, 83, 45, 0.2) !important;
            }
            
            /* Light Theme - Welcome Page */
            body.theme-light .bg-gradient-to-br.from-indigo-600.to-purple-600 {
                background: linear-gradient(to bottom right, #0A2540, #0C2F52) !important;
            }
            
            /* Light Theme - Green Colors (for Analytics Goal Card) */
            body.theme-light .bg-gradient-to-br.from-emerald-600\/30.via-green-600\/30.to-teal-600\/30 {
                background: linear-gradient(to bottom right, rgba(16, 185, 129, 0.3), rgba(34, 197, 94, 0.3), rgba(20, 184, 166, 0.3)) !important;
            }
            body.theme-light .bg-gradient-to-r.from-emerald-500.via-green-400.to-teal-400 {
                background: linear-gradient(to right, #10b981, #22c55e, #14b8a6) !important;
            }
            body.theme-light .border-emerald-500\/50,
            body.theme-light .border-emerald-500\/30 {
                border-color: rgba(16, 185, 129, 0.5) !important;
            }
            body.theme-light .text-emerald-200,
            body.theme-light .text-emerald-300 {
                color: #14532D !important;
            }
            body.theme-light .bg-emerald-500\/20 {
                background-color: rgba(16, 185, 129, 0.2) !important;
            }
            body.theme-light .bg-gradient-to-r.from-emerald-500.via-green-500.to-teal-500 {
                background: linear-gradient(to right, #10b981, #22c55e, #14b8a6) !important;
            }
            
            /* Light Theme - Additional Enhancements */
            body.theme-light .shadow-xl,
            body.theme-light .shadow-2xl {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04) !important;
            }
            body.theme-light .shadow-lg {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
            }
            body.theme-light .backdrop-blur-sm {
                backdrop-filter: blur(8px);
                background-color: rgba(255, 255, 255, 0.95) !important;
            }
            
            /* Light Theme - Purple/Violet/Fuchsia gradients for dashboard */
            body.theme-light .bg-violet-500\/20,
            body.theme-light .bg-purple-500\/20,
            body.theme-light .bg-fuchsia-500\/20 {
                background-color: rgba(10, 37, 64, 0.08) !important;
            }
            body.theme-light .text-violet-300,
            body.theme-light .text-purple-300,
            body.theme-light .text-fuchsia-300 {
                color: #0A2540 !important;
            }
            body.theme-light .border-violet-500\/40,
            body.theme-light .border-purple-500\/40,
            body.theme-light .border-fuchsia-500\/40 {
                border-color: rgba(10, 37, 64, 0.2) !important;
            }
            
            /* Light Theme - Progress bars and charts */
            body.theme-light .bg-slate-800\/80 {
                background-color: #e5e7eb !important;
            }
            body.theme-light .bg-slate-900\/60,
            body.theme-light .bg-slate-900\/70 {
                background-color: #F9FAFB !important;
            }
            
            /* Light Theme - Cards with better shadows */
            body.theme-light .rounded-2xl,
            body.theme-light .rounded-xl {
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
            }
            
            /* Light Theme - Button enhancements */
            body.theme-light button.bg-slate-800,
            body.theme-light .bg-slate-800 button {
                background-color: #F5F6F8 !important;
                color: #1F2933 !important;
            }
            body.theme-light button.bg-slate-800:hover {
                background-color: #e5e7eb !important;
            }
            
            /* Light Theme - Active states */
            body.theme-light .bg-indigo-600.text-white,
            body.theme-light a.bg-indigo-600 {
                background-color: #0A2540 !important;
                color: #FFFFFF !important;
            }
        </style>
        <script>
            function setTheme(mode) {
                const body = document.body;
                if (mode === 'light') {
                    body.classList.remove('theme-dark');
                    body.classList.add('theme-light');
                    document.documentElement.classList.remove('dark');
                } else {
                    body.classList.remove('theme-light');
                    body.classList.add('theme-dark');
                    document.documentElement.classList.add('dark');
                }
                localStorage.setItem('theme', mode);
                const toggleLabel = document.getElementById('theme-toggle-label');
                if (toggleLabel) {
                    toggleLabel.textContent = mode === 'light' ? 'Light' : 'Dark';
                }
            }
            document.addEventListener('DOMContentLoaded', () => {
                const saved = localStorage.getItem('theme') || 'dark';
                setTheme(saved);
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    </head>
    <body class="font-sans antialiased theme-dark">
        <div class="min-h-screen/ bg-gray-100/0">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-slate-900/80 border-b border-slate-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if (session('status'))
                <x-flash-message :message="session('status')" type="success" />
            @endif
            @if (session('error'))
                <x-flash-message :message="session('error')" type="error" />
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
