<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SecureVault') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden selection:bg-emerald-500 selection:text-white">
        
        <!-- Navbar -->
        <nav class="absolute top-0 left-0 right-0 z-50 py-6">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <x-application-logo class="w-12 h-12" />
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">{{ config('app.name', 'ZK FileShare') }}</span>
                </div>
                
                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-medium text-gray-600 hover:text-emerald-600 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-emerald-600 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white font-medium hover:bg-gray-800 transition shadow-lg hover:shadow-xl">Get Started</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative min-h-screen flex items-center pt-20">
            <!-- Background Decors -->
            <div class="absolute top-0 right-0 -mr-48 -mt-48 w-96 h-96 rounded-full bg-emerald-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 right-48 w-96 h-96 rounded-full bg-green-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-8 left-20 w-96 h-96 rounded-full bg-teal-300 mix-blend-multiply filter blur-3xl opacity-50 animate-blob" style="animation-delay: 4s;"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 w-full flex flex-col md:flex-row items-center gap-12">
                
                <div class="w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left">
                    <div class="inline-flex items-center space-x-2 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full text-sm font-semibold mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>100% Zero-Knowledge Architecture</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                        Share files with <br class="hidden md:block" />
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-green-600">Absolute Privacy</span>.
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-xl">
                        Your files are encrypted in your browser before they ever leave your device. We don't have the keys, we can't see your data, and hackers can't either.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-lg hover:from-emerald-700 hover:to-teal-700 transition shadow-lg shadow-emerald-200 transform hover:-translate-y-1">
                            Start Encrypting Free
                        </a>
                        <a href="#how-it-works" class="px-8 py-4 rounded-full bg-white border border-gray-200 text-gray-700 font-bold text-lg hover:bg-gray-50 transition">
                            Learn How It Works
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/2 mt-12 md:mt-0 relative">
                    <!-- Glassmorphism card -->
                    <div class="relative bg-white/40 backdrop-blur-xl border border-white/60 p-8 rounded-3xl shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                        <div class="absolute -top-6 -left-6 w-12 h-12 bg-white rounded-xl shadow-lg border border-gray-100 flex items-center justify-center animate-bounce">
                            <span class="text-xl">🔒</span>
                        </div>
                        
                        <div class="bg-gray-900 rounded-2xl p-6 text-green-400 font-mono text-sm shadow-inner overflow-hidden relative">
                            <div class="absolute top-3 left-4 flex space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="mt-6 space-y-2 opacity-80">
                                <p>> Attempting to read metadata...</p>
                                <p class="text-red-400">> Error: Unreadable block data.</p>
                                <p>> Decrypting file payload...</p>
                                <p class="text-red-400">> Error: Missing AES-256-GCM Key.</p>
                                <p class="text-emerald-300 mt-4">> SYSTEM: Zero-Knowledge mechanism operating perfectly. Your data remains yours.</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-between items-center bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-full flex justify-center items-center text-emerald-600">
                                    📁
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">Design_System_Final.zip</p>
                                    <p class="text-xs text-gray-500">Encrypted • 4.2 MB</p>
                                </div>
                            </div>
                            <button class="text-emerald-600 text-sm font-semibold hover:bg-emerald-50 px-3 py-1.5 rounded-lg transition">Share securely</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- What is Zero Knowledge? -->
        <section id="how-it-works" class="py-24 bg-white relative z-20 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-gray-900 mb-6">Why Zero-Knowledge?</h2>
                    <p class="text-lg text-gray-600">Traditional cloud providers hold the keys to your data, meaning employees or hackers can access your files. We use End-to-End Encryption so only you and your recipients can decode the contents.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition duration-300 group">
                        <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-teal-600 group-hover:text-white transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">1. Locally Encrypted</h3>
                        <p class="text-gray-600">Your browser generates a strong military-grade AES-256 key and encrypts the file before uploading.</p>
                    </div>
                    
                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition duration-300 group">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">2. Secret Share Links</h3>
                        <p class="text-gray-600">When you generate a share link, the decryption key is embedded in the URL fragment (#) which is never sent to the server.</p>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl transition duration-300 group">
                        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">3. We Are Blind</h3>
                        <p class="text-gray-600">Our database contains only encrypted blobs and obfuscated names. If our servers are seized, your data remains completely locked.</p>
                    </div>
                </div>
            </div>
        </section>

        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
        </style>
    </body>
</html>
