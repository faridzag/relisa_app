<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Computed;

class EventRegistrations extends Component
{
    public Event $event;

    #[Rule('sometimes|min:3|max:255')]
    public string $message;
    public function postRegistration()
    {
        $this->validateOnly('message');
        $this->event->registrations()->create([
            'message' => $this->message,
            'user_id' => auth()->id()
        ]);
        $this->reset('message');
    }

    #[Computed()]
    public function registrations()
    {
        return $this?->event?->registrations();
    }

    #[Computed()]
    public function isEditor()
    {
        $user = Auth::user();
        return $user->isAdmin() || $user->isEventManager();
    }

    #[Computed()]
    public function isRegistered()
    {
        $registeredUser = Registration::where('user_id', auth()->id())
        ->where('event_id', $this->event->id)
        ->exists();
        return $registeredUser;
    }

    public function render()
    {
        return view('livewire.event-registrations');
    }
}
