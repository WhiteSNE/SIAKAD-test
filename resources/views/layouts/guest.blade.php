<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIM PKL - Login') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="w-80 sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-200">
            
            <div class="text-center mb-8">
                {{-- Bagian Logo: Menggunakan flex justify-center untuk menengahkan --}}
                <div class="flex justify-center mb-4">
                    <a href="/">
                        <x-application-logo class="w-24 h-24 fill-current text-gray-500" />
                    </a>
                </div>

                {{-- Teks Judul --}}
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Sistem Monitoring PKL</h1>
                <p class="text-sm text-gray-500 font-medium">SMK NEGERI 4 MADIUN</p>
            </div>

            {{ $slot }}

        </div>
        
        <div class="mt-6 text-center text-gray-400 text-xs">
            &copy; {{ date('Y') }} SIM PKL SMKN 4 Madiun.
        </div>
    </div>
</body>

</html>