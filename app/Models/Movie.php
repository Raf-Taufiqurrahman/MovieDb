<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['tmdb_id', 'title', 'slug', 'runtime', 'lang', 'overview', 'poster'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
