<div class=" px-3 lg:px-7 py-6">
    <div class="flex justify-between items-center border-b border-gray-100">
        <div class="text-gray-600">
            @if ($this->activeCategory || $search)
                <button class="mr-4 text-blue-800" wire:click="clearFilters()">X</button>
            @endif
            @if($this->activeCategory)
            Postingan dari kategori :
            <x-badge wire:navigate href="{{ route('events.index', ['category' => $this->activeCategory->slug]) }}">{{ $this->activeCategory->title }}</x-badge>
            @endif
            @if($search)
            <span class="ml-2">
                Kata kunci : <strong>{{ $search }}</strong>
            </span>
            @endif
        </div>
        <div class="flex items-center space-x-4 font-light ">
            <button class="{{ $sort === 'desc' ? 'text-gray-900 py-4 border-b border-gray-700' : 'text-gray-500' }} py-4" wire:click="setSort('desc')">Terbaru</button>
            <button class="{{ $sort === 'asc' ? 'text-gray-900 py-4 border-b border-gray-700' : 'text-gray-500' }} py-4" wire:click="setSort('asc')">Terlama</button>
        </div>
    </div>
    <div class="py-4">
        @foreach($this->events as $event)
        <x-events.event-item wire:key="{{ $event->id }}" :event="$event"/>
        @endforeach
    </div>
    <div class="my-3">
        {{ $this->events->onEachSide(1)->links() }}
    </div>
</div>
