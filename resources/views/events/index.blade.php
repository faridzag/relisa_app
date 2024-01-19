<x-app-layout title="Acara">
        <div class="w-full grid grid-cols-4 gap-10">
            <div class="order-2 md:order-1 md:col-span-3 col-span-4">
                <livewire:event-list />
            </div>
            <div id="side-bar" class="order-1 md:order-2 border-t border-t-gray-100 md:border-t-none col-span-4 md:col-span-1 md:border-l border-gray-100 h-auto top-0">
            @include('events.part.search-box')
                <div class="hidden sm:block">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Kategori</h3>
                    <div class="flex flex-wrap justify-start gap-2">
                    @foreach ($categories as $category)
                    <x-events.category-badge :category="$category" />
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
