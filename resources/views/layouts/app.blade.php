<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      x-init="$watch('theme', val => localStorage.setItem('theme', val))" 
      :class="{ 'dark': theme === 'dark' }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Express Cashier') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: { 
                    extend: { 
                        colors: { primary: '#cc0000' },
                        fontFamily: { sans: ['Poppins', 'sans-serif'] }
                    } 
                }
            }
        </script>
        
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Poppins', sans-serif; }
        </style>

        @vite(['resources/js/app.js'])
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900 dark:bg-[#121212] dark:text-gray-100 transition-colors duration-300">
        
        <div class="flex h-screen overflow-hidden">
            <aside class="w-64 bg-white dark:bg-[#1e1e1e] border-r border-gray-200 dark:border-gray-800 flex flex-col justify-between transition-colors duration-300 z-10">
                <div>
                    <div class="p-6 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=ffd54f&color=000" alt="Profile" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-bold text-sm tracking-tight">{{ auth()->user()->name ?? 'Admin' }}</h3>
                        </div>
                    </div>

                    <div class="px-6 mb-6">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-primary dark:text-gray-200 transition-all">
                        </div>
                    </div>

                    <nav class="px-4 space-y-2">
                        <a href="{{ route('dashboard') }}" wire:navigate 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-lg shadow-red-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'group-hover:text-primary transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="font-semibold text-sm">Dashboard</span>
                        </a>

                        <a href="{{ route('hitung-penghasilan') }}" wire:navigate 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('hitung-penghasilan') ? 'bg-primary text-white shadow-lg shadow-red-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('hitung-penghasilan') ? 'text-white' : 'group-hover:text-primary transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium text-sm">Hitung Penghasilan</span>
                        </a>

                        <a href="{{ route('print-color-analysis') }}" wire:navigate 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('print-color-analysis') ? 'bg-primary text-white shadow-lg shadow-red-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('print-color-analysis') ? 'text-white' : 'group-hover:text-primary transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span class="font-medium text-sm">Print Color Analysis</span>
                        </a>
                    </nav>
                </div>

                <div class="p-6 border-t border-gray-200 dark:border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-gray-600 dark:text-gray-400 hover:text-primary transition-colors mb-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="font-medium text-sm tracking-wide">Keluar</span>
                        </button>
                    </form>

                    <div class="flex items-center justify-between px-4">
                        <span class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400" x-text="theme === 'dark' ? 'Dark' : 'Light'"></span>
                        <button @click="theme = theme === 'dark' ? 'light' : 'dark'" class="w-12 h-6 rounded-full bg-gray-200 dark:bg-primary relative transition-colors duration-300 flex items-center p-1">
                            <div class="w-4 h-4 rounded-full bg-white shadow-md transform transition-transform duration-300 flex items-center justify-center" :class="theme === 'dark' ? 'translate-x-6' : 'translate-x-0'">
                                <svg x-cloak x-show="theme === 'light'" class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                                <svg x-cloak x-show="theme === 'dark'" class="w-3 h-3 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </button>
                    </div>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-gray-50 dark:bg-[#121212] transition-colors duration-300">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>