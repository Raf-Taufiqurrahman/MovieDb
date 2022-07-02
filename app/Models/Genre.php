<?php

namespace App\Models;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, Slug;

    protected $fillable = ['tmdb_id', 'name', 'slug'];
}
