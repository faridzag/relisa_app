<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('categories', Carbon::now()->addDays(1), function () {
            return Category::whereHas('events', function ($query) {
                $query->published();
            })->take(10)->get();
        });

        return view(
            'events.index',
            [
                'categories' => $categories
            ]
        );
    }
    public function show(Event $event)
    {
        return view(
            'events.show',
            [
                'event' => $event
            ]
        );
    }
}
