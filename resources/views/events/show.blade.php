<x-app-layout :title="$event->title">
    <article class="col-span-4 md:col-span-3 mt-10 mx-auto py-5 w-full" style="max-width:700px">
        <img class="w-full my-2 rounded-lg" src="{{ $event->getThumbnailImage() }}" alt="thumbnail">
        <h1 class="text-4xl font-bold text-left text-gray-800">
            {{ $event->title }}
        </h1>
        <div class="mt-2 flex justify-between items-center">
            <div class="flex py-5 text-base items-center">
                <x-events.author :author="$event->author" />
                <span class="text-gray-500 text-sm"></span>
            </div>
            <div class="flex items-center">
                <span class="text-gray-500 mr-2">{{ $event->published_at->diffForHumans() }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3"
                    stroke="currentColor" class="w-5 h-5 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="flex items-center space-x-4 mt-10 mb-16">
            @foreach ($event->categories as $category)
            <x-events.category-badge :category="$category" />
            @endforeach
        </div>
        <hr>

        <div class="mt-8 py-3 prose text-gray-800 text-lg text-justify">
            <span class="text-gray-500 text-sm">Lokasi: {!! $event->location !!}</span>
            <p>{!! $event->description !!}</p>
        </div>
        <livewire:event-registrations :key="$event->id" :$event />

    </article>
</x-app-layout>
