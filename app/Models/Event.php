<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'slug',
        'start_date',
        'published_at',
        'location',
        'description',
        'message',
        'status',
        'user_id',
    ];


    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopePublished($query)
    {
        $query->where('published_at', '<=', Carbon::now());
    }

    public function scopeWithCategory($query, string $category)
    {
        $query->whereHas('categories', function($query) use ($category) {
            $query->where('slug', $category);
        });
    }

    public function getExcerpt()
    {
        return Str::limit(strip_tags($this->description), 100);
    }

    public function getLocation()
    {
        return Str::limit(strip_tags($this->location), 20);
    }

    public function getThumbnailImage()
    {
        $isUrl = str_contains($this->image, 'http');

        return ($isUrl) ? $this->image : Storage::disk('public')->url($this->image);
    }
}
