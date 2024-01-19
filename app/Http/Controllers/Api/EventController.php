<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Exception;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $events = Event::with('categories')->published()->get();
            return response()->json([
                'status' => true,
                'message' => 'List dari Acara',
                'data' => $events
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:events|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'slug' => 'required|unique:events|max:255',
            'start_date' => 'required|date',
            'location' => 'required|max:255',
            'description' => 'required',
            'status' => 'required|in:open,closed,ongoing,done'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->first()
            ], 422);
        }

        try {
            $payload = $validator->validated();
            $imagePath = $request->file('image')->store('event_images');
            $event = Event::create([
                'title' => $payload['title'],
                'slug' => $payload['slug'],
                'start_date' => $payload['start_date'],
                'location' => $payload['location'],
                'description' => $payload['description'],
                'status' => $payload['status'],
                'image' => $imagePath,
                'user_id' => Auth::user()->id,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Event berhasil dibuat',
                'data' => $event
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal membuat Event',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function show(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Event',
            'data' => $event
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:events,title,' . $id . '|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'slug' => 'required|unique:events,slug,' . $id . '|max:255',
            'start_date' => 'required|date',
            'location' => 'required|max:255',
            'description' => 'required',
            'status' => 'required|in:open,closed,ongoing,done'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->first()
            ], 422);
        }

        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        try {
            $payload = $validator->validated();

            // Untuk update image
            $imagePath = $event->image;
            if ($request->hasFile('image')) {
                Storage::delete($event->image);
                $imagePath = $request->file('image')->store('event_images');
            }

            $event->update([
                'title' => $payload['title'],
                'slug' => $payload['slug'],
                'start_date' => $payload['start_date'],
                'location' => $payload['location'],
                'description' => $payload['description'],
                'status' => $payload['status'],
                'image' => $imagePath
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Event berhasil Diupdate',
                'data' => $event
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupdate Event',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        try {
            if ($event->image) {
                Storage::delete($event->image);
            }

            $event->delete();

            return response()->json([
                'status' => true,
                'message' => 'Event berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus Event',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
