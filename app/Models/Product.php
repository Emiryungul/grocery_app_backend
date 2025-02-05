<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description', 'stock', 'category_id', 'image_path'];
    protected $appends = ['image_url']; 

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getImageUrlAttribute()
    {
        return url('storage/images/' . $this->image_path);
    }
}