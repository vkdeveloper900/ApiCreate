<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'album_id',
        'title',
        'url',
        'thumbnail_url',
    ];

    // Each Photo belongs to an Album
    public function album()
    {
        return $this->belongsTo(Albums::class);
    }
}
