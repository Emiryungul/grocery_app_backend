<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path', // Add image path field
    ];
    
    public function getImageUrlAttribute()
    {
        return url('storage/images/' . $this->image_path);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}