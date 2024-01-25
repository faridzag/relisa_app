<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Framework\Exception;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
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
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            $event = Event::create([
                'title' => $payload['title'],
                'slug' => $payload['slug'],
                'start_date' => $payload['start_date'],
                'location' => $payload['location'],
                'description' => $payload['description'],
                'status' => $payload['status'],
                'image' => 'events/thumbnails/' . $imageName,
                'user_id' => Auth::user()->id,
            ]);

            Storage::disk('public')->put('events/thumbnails/'. $imageName, file_get_contents($request->image));
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
            if ($request->hasFile('image')) {
                // Hapus gambar
                if ($event->image !== null && Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }

                // Simpan gambar baru
                $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
                $event->image = $imageName;
                Storage::disk('public')->put('events/thumbnails/' . $imageName, file_get_contents($request->image));
            }

            $event->update([
                'title' => $payload['title'],
                'start_date' => $payload['start_date'],
                'location' => $payload['location'],
                'description' => $payload['description'],
                'status' => $payload['status']
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
            if ($event->image !== null && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
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
