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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            document.documentElement.classList.add('dark');
        </script>
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="bg-slate-900/70 border border-slate-800 px-6 py-4 rounded-full text-sm text-slate-200 shadow-lg">
                Skills Builder System
            </div>
            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-slate-900/80 border border-slate-800 shadow-xl overflow-hidden sm:rounded-2xl backdrop-blur">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
