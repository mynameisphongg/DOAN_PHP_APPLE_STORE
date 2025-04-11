<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // Nhớ import
class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Đảm bảo đúng tên bảng

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock', 
        'image', 
        'category_id'
    ];

    // Thiết lập quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
