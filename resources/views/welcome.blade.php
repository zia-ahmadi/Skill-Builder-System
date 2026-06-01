<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Skills Builder System') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            body.theme-light nav,
            body.theme-light header {
                background-color: #FFFFFF !important;
                border-color: #e5e7eb !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            }
            
            /* Light Theme - Text Colors - Enhanced */
            body.theme-light .text-white,
            body.theme-light .text-slate-100 { color: #1F2933 !important; }
            body.theme-light .text-slate-200 { color: #374151 !important; }
            body.theme-light .text-slate-300 { color: #1F2933 !important; }
            body.theme-light .text-slate-400 { color: #6B7280 !important; }
            body.theme-light .text-indigo-300 { color: #0A2540 !important; }
            
            /* Light Theme - Background Colors - Enhanced */
            body.theme-light .bg-slate-900\/70 {
                background-color: #FFFFFF !important;
                color: #1F2933 !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
            }
            body.theme-light .bg-indigo-600,
            body.theme-light .bg-indigo-500 {
                background-color: #0A2540 !important;
                color: #FFFFFF !important;
                box-shadow: 0 4px 6px -1px rgba(10, 37, 64, 0.2), 0 2px 4px -1px rgba(10, 37, 64, 0.1) !important;
            }
            body.theme-light .hover\:bg-indigo-500:hover {
                background-color: #0C2F52 !important;
            }
            body.theme-light .bg-slate-800 {
                background-color: #F5F6F8 !important;
            }
            body.theme-light .hover\:bg-slate-700:hover {
                background-color: #e5e7eb !important;
            }
            body.theme-light .border-slate-700 {
                border-color: #e5e7eb !important;
            }
            body.theme-light .border-slate-800 {
                border-color: #e5e7eb !important;
            }
                background-color: #e5e7eb !important;
            }
            
            /* Light Theme - Borders */
            body.theme-light .border-slate-800 {
                border-color: #e5e7eb !important;
            }
            body.theme-light .border-slate-700 {
                border-color: #e5e7eb !important;
            }
            
            /* Light Theme - Gradient */
            body.theme-light .bg-gradient-to-br.from-indigo-600.to-purple-600 {
                background: linear-gradient(to bottom right, #0A2540, #0C2F52) !important;
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
    </head>
    <body class="font-sans antialiased theme-dark min-h-screen">
        <!-- Navigation Bar -->
        @include('layouts.navigation')

        <!-- Hero Section -->
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh-4rem)] py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl w-full text-center space-y-8">
                <!-- Big Logo -->
                <div class="flex flex-col items-center justify-center space-y-4 mb-12">
                    <div class="flex items-center justify-center h-32 w-32 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white font-bold tracking-tight text-5xl shadow-2xl transform transition-transform hover:scale-105">
                        SBS
                    </div>
                    <div class="space-y-2">
                        <h1 class="text-6xl md:text-7xl font-bold text-white tracking-tight">
                            Skills Builder
                        </h1>
                        <h2 class="text-3xl md:text-4xl font-semibold text-indigo-300">
                            System
                        </h2>
                    </div>
                    <p class="text-xl text-slate-300 max-w-2xl mx-auto mt-6">
                        Track your learning journey, build skills, and achieve your goals. 
                        The ultimate platform for managing your study sessions and monitoring progress.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="p-6 bg-slate-900/70 border border-slate-800 rounded-xl shadow-lg">
                        <div class="text-4xl mb-3">📚</div>
                        <h3 class="text-lg font-semibold text-white mb-2">Track Skills</h3>
                        <p class="text-sm text-slate-400">Organize and manage all your learning skills in one place.</p>
                    </div>
                    <div class="p-6 bg-slate-900/70 border border-slate-800 rounded-xl shadow-lg">
                        <div class="text-4xl mb-3">📊</div>
                        <h3 class="text-lg font-semibold text-white mb-2">Analytics</h3>
                        <p class="text-sm text-slate-400">Get detailed insights into your learning progress and patterns.</p>
                    </div>
                    <div class="p-6 bg-slate-900/70 border border-slate-800 rounded-xl shadow-lg">
                        <div class="text-4xl mb-3">🎯</div>
                        <h3 class="text-lg font-semibold text-white mb-2">Set Goals</h3>
                        <p class="text-sm text-slate-400">Define learning goals and track your progress towards them.</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-12">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition-colors text-lg">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition-colors text-lg">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-lg transition-colors text-lg border border-slate-700">
                            Log In
                        </a>
                    @endauth
                </div>
                </div>
        </div>
    </body>
</html>
