<?php

namespace App\Models;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, Slug;

    protected $fillable = [
        'name', 'slug',
    ];
}
