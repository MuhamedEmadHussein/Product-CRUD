<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\Product\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'is_active'];

    // protected static function newFactory(): ProductFactory
    // {
    //     // return ProductFactory::new();
    // }
    
}
