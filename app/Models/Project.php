<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
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
