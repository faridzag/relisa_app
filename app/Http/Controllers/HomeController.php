<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $latestEvents = Event::with('categories')->published()->latest('published_at')->take(6)->get();
        return view('home', [
            'latestEvents' => $latestEvents,
        ]);
    }
}
