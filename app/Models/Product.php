<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 
        'sale_price', 'stock', 'image', 'description', 
        'care_guide', 'sunlight', 'water'
    ];

    // Một sản phẩm cây cảnh thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}