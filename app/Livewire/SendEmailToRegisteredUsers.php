<?php

namespace App\Livewire;

use Livewire\Component;

class SendEmailToRegisteredUsers extends Component
{
    public $selectedEventId = null;

    public function sendEmails()
    {
        // Retrieve registered users for the selected event
        $registeredUsers = User::whereHas('registrations', function ($query) {
            $query->where('event_id', $this->selectedEventId);
        })->get();

        foreach ($registeredUsers as $user) {
            // Send email using Laravel Mail
            Mail::to($user->email)->send(new EventRegistrationEmail($user));
        }

        // Display success message or handle errors
        session()->flash('message', 'Emails sent successfully!');
    }

    public function render()
    {
        return view('livewire.send-email-to-registered-users');
    }
}
