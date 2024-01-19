<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Exception;

class EventRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $registration = Registration::all();
            return response()->json([
                'status' => true,
                'message' => 'Data Pendaftaran Acara',
                'data'=> $registration
            ], 200 );
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'message' => 'nullable|string|max:255',
            'status' => 'sometimes|in:accepted,rejected,pending',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Tidak dapat menemukan Event",
            ], 422);
        }
        if ($user->events()->where('event_id', $request->event_id)->exists()) {
            return response()->json([
                'message' => 'Anda sudah terdaftar di Acara.'
            ], 400);
        }
        $payload = $validator->validated();

        $imagePath = $request->file('image')->store('registration_images');
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $payload['event_id'],
            'message' => $payload['message'],
            'status' => $payload['status'],
            'image' => $imagePath
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Pendaftaran Acara berhasil',
            'data' => $registration,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registration = Registration::find($id);

        if($registration){
            return response()->json([
                'status' => true,
                'message' => 'Data Pendaftaran Acara',
                'data' => $registration,
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data Pendaftaran tidak ditemukan'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $registration = Registration::find($id);
        $validator = Validator::make($request->all(), [
            'message' => 'nullable|string|max:255',
            'status' => 'sometimes|in:accepted,rejected,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->first(),
            ], 422);
        }

        $registration->update($validator->validated());
        return response()->json([
            'status' => true,
            'message' => 'Pendaftaran berhasil diperbarui',
            'data' => $registration,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $registration = Registration::find($id);
        if(empty($registration)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Pendaftaran Tidak Ditemukan'
            ], 404);
        }
        $registration->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Pendaftaran Sukses Terhapus'
        ], 200);
    }
}
