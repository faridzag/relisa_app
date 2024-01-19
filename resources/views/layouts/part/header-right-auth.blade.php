<div class="flex ms-3 relative space-x-4">
    <!--
    <x-dropdown align="top" width="48">
        <x-slot name="trigger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
        </x-slot>
        <x-slot name="content">
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ __('Notifikasi') }}
            </div>
        </x-slot>
    </x-dropdown>
    -->
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
            </button>
            @else
            <span class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                    {{ Auth::user()->name }}

                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </span>
            @endif
        </x-slot>

        <x-slot name="content">
            <!-- Account Management -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ __('Kelola Akun') }}
            </div>

            <x-dropdown-link wire:navigate href="{{ route('profile.show') }}">
                {{ __('Profil') }}
            </x-dropdown-link>

            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
            <x-dropdown-link wire:navigate href="{{ route('api-tokens.index') }}">
                {{ __('API Tokens') }}
            </x-dropdown-link>
            @endif

            <div class="border-t border-gray-200"></div>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <x-dropdown-link href="{{ route('logout') }}"
                    @click.prevent="$root.submit();">
                    {{ __('Keluar') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>

