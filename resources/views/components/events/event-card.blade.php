@props(['event'])
<div {{ $attributes }}>
    <a wire:navigate href="{{ route('events.show', $event->slug) }}" >
        <div>
            <img class="w-full rounded-xl" src="{{ $event->getThumbnailImage() }}">
        </div>
    </a>
    <div class="mt-3">
        <div class="flex items-center mb-2 gap-x-2">
            @if($category = $event->categories->first())
            <x-events.category-badge :category="$category" />
            @endif
            <p class="text-gray-500 text-sm">{{ $event->published_at }}</p>
        </div>
        <a wire:navigate href="{{ route('events.show', $event->slug) }}" class="text-xl font-bold text-gray-900">{{ $event->title }}</a>
    </div>
</div>
