<nav class="flex items-center justify-between py-3 px-6 border-b border-gray-100">
    <div id="header-left" class="flex items-center">
        <div class="text-gray-800 font-semibold">
            <div class="md:flex md:justify-center md:items-center">
                <x-application-mark />
            </div>
        </div>
         <div class="top-menu ml-10">
            <div class="flex space-x-4">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link href="{{ route('events.index') }}" :active="request()->routeIs('events.index')">
                    {{ __('Acara') }}
                </x-nav-link>
                @can('viewAdminPanel', App\Models\User::class)
                <x-nav-link :navigate='false' href="{{ route('filament.admin.home') }}" :active="request()->routeIs('filament.admin.auth.login')">
                    {{ __('Admin Panel') }}
                </x-nav-link>
                @endcan
                @can('viewAppPanel', App\Models\User::class)
                <x-nav-link :navigate='false' href="{{ route('filament.app.home') }}" :active="request()->routeIs('filament.admin.auth.login')">
                    {{ __('Acaraku') }}
                </x-nav-link>
                @endcan
            </div>
        </div>
    </div>
    <div id="header-right" class="flex items-center md:space-x-6">
        @auth
            @include('layouts.part.header-right-auth')
        @else
            @include('layouts.part.header-right-guest')
        @endauth
    </div>
</nav>
