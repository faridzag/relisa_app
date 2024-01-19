<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Registration extends Model
{
    use HasFactory;
    use Notifiable;

    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'event_id',
        'message',
        'image',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
