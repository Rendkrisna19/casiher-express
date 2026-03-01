<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

{{-- Halaman Login Responsif --}}
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 px-4">
    
    {{-- Card Container --}}
    <div class="w-full max-w-[420px] mt-6 px-6 py-8 sm:p-10 bg-white border border-red-500 shadow-[0_4px_20px_rgba(0,0,0,0.08)] rounded-xl relative overflow-hidden">
        
        {{-- Bagian Header & Logo --}}
        <div class="text-center mb-8">
            {{-- Logo Image --}}
            <div class="flex justify-center mb-4">
                {{-- Pastikan file gambar ada di public/assets/images/express-logo.png --}}
                <img src="{{ asset('assets/images/logo.jpg') }}" 
                     alt="EXPRESS Logo" 
                     class="h-12 sm:h-14 w-auto object-contain hover:scale-105 transition-transform duration-300">
            </div>

            {{-- Text Cashier --}}
            <h1 class="text-[#cc0000] text-2xl sm:text-3xl font-extrabold mb-2 tracking-tight">Cashier</h1>
            {{-- Subtitle --}}
            <h2 class="text-gray-600 font-semibold text-sm sm:text-base">Welcome Admin</h2>
        </div>

        {{-- Session Status (Pesan Error/Sukses Bawaan Breeze) --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            
            {{-- Input Username (Email) --}}
            <div class="mb-5 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#cc0000] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input type="email" wire:model="form.email" placeholder="Enter your username" 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-[#cc0000] text-sm placeholder-gray-400 bg-white transition-all duration-200" 
                    required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs text-red-600 font-medium" />
            </div>

            {{-- Input Password --}}
            <div class="mb-6 relative group" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#cc0000] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                
                <input :type="show ? 'text' : 'password'" wire:model="form.password" placeholder="Enter your password" 
                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-[#cc0000] text-sm placeholder-gray-400 bg-white transition-all duration-200" 
                    required autocomplete="current-password">
                
                {{-- Toggle Show Password --}}
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer z-10" @click="show = !show">
                    <svg x-show="!show" class="h-4 w-4 text-gray-400 hover:text-gray-600 transition" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4 text-gray-400 hover:text-gray-600 transition" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.414-1.414a1 1 0 001.414-1.414L3.707 2.293zM12.05 10.636l-2.686-2.686a4 4 0 015.372 5.372zm-4.1 4.1l-2.686-2.686a4 4 0 010-5.372 4 4 0 015.372 0zm8.192 2.318a9.963 9.963 0 01-4.142.928 10 10 0 114.142-18.913l-2.328 2.328a8 8 0 10-1.814 15.657z" clip-rule="evenodd" /></svg>
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs text-red-600 font-medium" />
            </div>

            {{-- Checkbox Remember Me --}}
            <div class="mb-8 flex items-center">
                <input type="checkbox" id="remember" wire:model="form.remember" 
                    class="h-4 w-4 text-[#cc0000] focus:ring-[#cc0000] focus:ring-offset-0 border-gray-300 rounded cursor-pointer transition">
                <label for="remember" class="ml-2 block text-xs sm:text-sm text-gray-600 select-none cursor-pointer hover:text-gray-800">
                    Masuk Sebagai Admin
                </label>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button type="submit" 
                    class="w-full bg-[#cc0000] hover:bg-red-700 focus:bg-red-800 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 ease-in-out text-sm tracking-wider flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98]"
                    wire:loading.attr="disabled"
                    wire:target="login">
                    <span wire:loading.remove wire:target="login">MASUK</span>
                    {{-- Spinner Loading --}}
                    <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
    {{-- Footer kecil (opsional) --}}
    <div class="mt-8 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} EXPRESS Cashier System.
    </div>
</div>