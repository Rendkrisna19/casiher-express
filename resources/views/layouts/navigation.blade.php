<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
        {{ __('Dashboard') }}
    </x-nav-link>
    
    <x-nav-link href="#" wire:navigate>
        {{ __('Ink Analyzer') }}
    </x-nav-link>

    <x-nav-link href="{{ route('hitung-penghasilan') }}" wire:navigate>
        {{ __('Cashier/POS') }}
    </x-nav-link>
</div>