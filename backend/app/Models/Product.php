<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'stock',
        'image',
        'category_name',
        'is_active'
    ];

   
}





