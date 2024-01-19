@props(['event'])
<article
    {{ $attributes->merge(['class' => '[&:not(:last-child)]:border-b border-gray-100 pb-10']) }}>
    <div class="article-body grid grid-cols-12 gap-3 mt-5 items-start">
        <div class="article-thumbnail col-span-4 flex items-center">
            <a wire:navigate href="{{ route('events.show', $event->slug) }}" >
                <img class="mw-100 mx-auto rounded-xl" src="{{ $event->getThumbnailImage() }}" alt="thumbnail">
            </a>
        </div>
        <div class="col-span-8">
            <div class="article-meta flex py-1 text-sm items-center">
                <x-events.author :author="$event->author" />
                <span class="text-gray-500 text-xs">. {{ $event->published_at->diffForHumans() }}</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">
                <a wire:navigate href="{{ route('events.show', $event->slug) }}" >
                    {{ $event->title }}
                </a>
            </h2>

            <p class="mt-2 text-base text-gray-700 font-light">
                {{ $event->getExcerpt() }}
            </p>
            <div class="article-actions-bar mt-6 flex items-center justify-between">
                <div class="flex gap-x-2">
                    <div class="flex gap-x-2">
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-500 text-sm">{{ $event->getLocation() }}</span>
                        </div>
                        @foreach ($event->categories as $category)
                        <x-events.category-badge :category="$category" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
