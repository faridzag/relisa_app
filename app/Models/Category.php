<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'slug',
        'text_color',
        'bg_color',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
