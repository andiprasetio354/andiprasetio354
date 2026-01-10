<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>@yield('title', 'Portfolio - Web Developer')</title>
        <meta name="description" content="@yield('description', 'Portofolio profesional web developer dengan spesialisasi Laravel dan JavaScript.')">
        <meta name="keywords" content="@yield('keywords', 'web developer, laravel, php, javascript, portfolio')">
        <meta name="robots" content="index, follow">

        <!-- Open Graph -->
        <meta property="og:title" content="@yield('title', 'Portfolio - Web Developer')">
        <meta property="og:description" content="@yield('description', 'Portofolio profesional web developer')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="@yield('image', config('app.url') . '/logo.png')">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'Portfolio')">
        <meta name="twitter:description" content="@yield('description', 'Web Developer Portfolio')">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @auth
                @include('layouts.navigation')
            @else
                <x-public-header />
            @endauth

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
