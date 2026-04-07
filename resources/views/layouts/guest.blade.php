<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SecureVault') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob { animation: blob 7s infinite; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 relative overflow-hidden">
        
        <!-- Background Decors -->
        <div class="fixed top-0 left-0 -ml-48 -mt-48 w-96 h-96 rounded-full bg-emerald-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="fixed top-1/2 right-0 w-96 h-96 rounded-full bg-green-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob" style="animation-delay: 2s;"></div>
        <div class="fixed bottom-0 left-1/2 w-96 h-96 rounded-full bg-teal-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob" style="animation-delay: 4s;"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center group">
                    <div class="transform group-hover:scale-105 transition duration-300">
                        <x-application-logo class="w-20 h-20" />
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-10 py-8 bg-white/80 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-3xl border border-white/60">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-emerald-800">Welcome to {{ config('app.name', 'ZK FileShare') }}</h2>
                    <p class="text-gray-500 text-sm mt-2">Zero-Knowledge Encrypted Sharing</p>
                </div>
                
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-xs text-gray-400 font-medium tracking-wide">END-TO-END ENCRYPTED</p>
        </div>
    </body>
</html>
