<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        
        <div class="bg-white border border-red-500 shadow-[0_0_15px_rgba(0,0,0,0.1)] p-10 w-full max-w-md relative">
            
            <div class="text-center mb-8">
                <div class="flex justify-center mb-2">
                    <div class="bg-gray-100 px-6 py-1 rounded-md border border-gray-200">
                        <span class="text-[#cc0000] font-bold italic text-3xl tracking-wider" style="font-family: Arial, sans-serif;">EXPRESS</span>
                    </div>
                </div>
                <h1 class="text-[#cc0000] text-4xl font-bold mb-4">Cashier</h1>
                <h2 class="text-gray-800 font-bold text-lg">Wellcome Admin</h2>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="email" name="email" :value="old('email')" placeholder="Enter your username" class="w-full pl-10 pr-3 py-2 border border-red-400 rounded-md focus:outline-none focus:ring-1 focus:ring-red-500 text-sm placeholder-gray-400" required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-4 relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="Enter your password" class="w-full pl-10 pr-10 py-2 border border-red-400 rounded-md focus:outline-none focus:ring-1 focus:ring-red-500 text-sm placeholder-gray-400" required autocomplete="current-password">
                    
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                        <svg class="h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-8 flex items-center">
                    <input type="checkbox" id="admin_check" name="remember" class="h-4 w-4 text-[#cc0000] focus:ring-[#cc0000] border-gray-400 rounded">
                    <label for="admin_check" class="ml-2 block text-xs text-gray-500">
                        Masuk Sebagai Admin
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#cc0000] hover:bg-red-800 text-white font-bold py-2.5 px-4 rounded-md transition duration-150 ease-in-out text-sm tracking-widest">
                        MASUK
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>