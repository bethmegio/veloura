<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    // Relationships
    public function gowns()
    {
        return $this->hasMany(Gown::class);
    }

    public function getGownCountAttribute()
    {
        return $this->gowns()->count();
    }
}