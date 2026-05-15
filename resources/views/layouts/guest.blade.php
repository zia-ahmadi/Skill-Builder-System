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
