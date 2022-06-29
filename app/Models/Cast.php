<?php

namespace App\Models;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cast extends Model
{
    use HasFactory, Slug;

    protected $fillable = ['tmdb_id', 'name', 'slug', 'photo'];

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn($photo) => 'https://image.tmdb.org/t/p/w500/'. $photo,
        );
    }
}
