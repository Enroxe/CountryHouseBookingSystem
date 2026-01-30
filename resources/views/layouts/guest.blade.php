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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col justify-center items-center px-4">

            <!-- Логотип -->
            <a href="/" class="mb-6">
                <x-application-logo class="w-24 h-24 mx-auto" />
            </a>

            <!-- Карточка формы -->
            <div class="
                w-full max-w-md
                bg-white
                px-6 py-6
                rounded-xl
                border-2 border-sky-600
                shadow-lg
            ">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>
