<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title'
    ];

    // One Album has many Photos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
