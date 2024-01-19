<?php

use App\Http\Controllers\EventController;
use App\Mail\TestMail;
use App\Mail\RegistrationAccepted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', HomeController::class)->name('home');
/*Route::get('/send-mail', function () {
    Mail::to('test@test.com')->send(new TestMail("Relisa"));
});

Route::get('/send-mail', function () {
    $user = Auth::user();
    Mail::to($user)->send(new RegistrationAccepted());
});*/

Route::get('/event', [EventController::class, 'index'])->name('events.index');
Route::get('/event/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    /*Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');*/
});
