<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'tech_stack',
        'image',
        'link',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];
}
