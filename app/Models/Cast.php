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

}
