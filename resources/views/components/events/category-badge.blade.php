@props(['category'])
<x-badge wire:navigate href="{{ route('events.index', ['category' => $category->slug]) }}">{{ $category->title }}</x-badge>
