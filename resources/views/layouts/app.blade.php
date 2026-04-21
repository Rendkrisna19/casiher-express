<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light', sidebarOpen: false }" 
      x-init="$watch('theme', val => localStorage.setItem('theme', val))" 
      :class="{ 'dark': theme === 'dark' }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ $title ?? 'Express Cashier' }}</title>

        <link rel="icon" href="{{ asset('assets/images/logo.jpg') }}" type="image/jpeg">
        <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.jpg') }}">

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
            /* Memastikan scrollbar rapi */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            .dark ::-webkit-scrollbar-thumb { background: #334155; }
        </style>

        @vite(['resources/js/app.js'])
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-[#121212] dark:text-gray-100 transition-colors duration-300">
        
        <div class="flex h-screen overflow-hidden w-full">
            
            <div x-show="sidebarOpen" 
                 x-transition.opacity 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/60 z-20 lg:hidden backdrop-blur-sm" x-cloak>
            </div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                   class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-[#1e1e1e] border-r border-gray-200 dark:border-gray-800 flex flex-col justify-between transition-transform duration-300 lg:static lg:translate-x-0 shadow-2xl lg:shadow-none">
                
                <div class="overflow-y-auto h-full flex flex-col">
                    <div class="p-6 flex items-center justify-between lg:justify-start gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center overflow-hidden shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=ffd54f&color=000" alt="Profile" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm tracking-tight truncate max-w-[100px]">{{ auth()->user()->name ?? 'Admin' }}</h3>
                                <p class="text-[10px] text-green-500 font-bold uppercase tracking-wider">Online</p>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="px-6 mb-4 hidden lg:flex items-center gap-3">
                         <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover shadow-sm">
                         <h2 class="font-black text-sm tracking-tight text-gray-800 dark:text-white uppercase">Express Cashier</h2>
                    </div>

                    <div class="px-6 mb-6">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary dark:text-gray-200 transition-all outline-none">
                        </div>
                    </div>

                    <nav class="px-4 space-y-1.5 flex-1">
                        @php
                            $menus = [
                                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route' => 'hitung-penghasilan', 'label' => 'Hitung Penghasilan', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['route' => 'print-color-analysis', 'label' => 'Color Analysis', 'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z'],
                                ['route' => 'stok-barang', 'label' => 'Stok Barang', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14v10m0-10L4 7m8 4L4 7m0 0v10l8 4'],
                                ['route' => 'transaksi-stok', 'label' => 'Transaksi (Scan)', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                                ['route' => 'riwayat-transaksi', 'label' => 'Riwayat Transaksi', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                            ];
                        @endphp

                        @foreach($menus as $menu)
                            @php $isActive = request()->routeIs($menu['route']); @endphp
                            <a href="{{ route($menu['route']) }}" wire:navigate @click="sidebarOpen = false"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-primary text-white shadow-lg shadow-red-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <svg class="w-5 h-5 transition-colors {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                                </svg>
                                <span class="font-medium text-sm {{ $isActive ? 'font-bold' : '' }}">{{ $menu['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="p-6 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-[#1e1e1e]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-gray-600 dark:text-gray-400 hover:text-primary transition-colors mb-6 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="font-bold text-sm tracking-wide">Keluar</span>
                        </button>
                    </form>

                    <div class="flex items-center justify-between px-4">
                        <span class="text-xs font-black uppercase tracking-widest text-gray-400" x-text="theme === 'dark' ? 'DARK MODE' : 'LIGHT MODE'"></span>
                        <button @click="theme = theme === 'dark' ? 'light' : 'dark'" class="w-12 h-6 rounded-full bg-gray-200 dark:bg-primary relative transition-colors duration-300 flex items-center p-1 focus:outline-none">
                            <div class="w-4 h-4 rounded-full bg-white shadow-md transform transition-transform duration-300 flex items-center justify-center" :class="theme === 'dark' ? 'translate-x-6' : 'translate-x-0'">
                                <svg x-cloak x-show="theme === 'light'" class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                                <svg x-cloak x-show="theme === 'dark'" class="w-3 h-3 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </button>
                    </div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
                
                <header class="lg:hidden flex items-center justify-between bg-white dark:bg-[#1e1e1e] p-4 border-b border-gray-200 dark:border-gray-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover">
                        <h1 class="font-black text-lg tracking-tight text-gray-800 dark:text-white uppercase">Express Cashier</h1>
                    </div>
                    <button @click="sidebarOpen = true" class="p-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-gray-600 dark:text-gray-300 focus:outline-none hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </header>

                <main class="flex-1 overflow-y-auto p-4 sm:p-8 w-full">
                    {{ $slot }}
                </main>

            </div>
        </div>

        @livewireScripts
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('swal', (event) => {
                    const data = event[0];
                    Swal.fire({
                        title: data.title,
                        text: data.text,
                        icon: data.icon,
                        timer: data.timer || null,
                        showConfirmButton: data.timer ? false : true,
                        confirmButtonColor: '#cc0000',
                    });
                });
            });
        </script>
    </body>
</html>